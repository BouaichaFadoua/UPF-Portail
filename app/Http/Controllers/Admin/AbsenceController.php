<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Justificatif;
use App\Models\Absence;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\NotificationUpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id = $request->input('groupe_id');
        $search = $request->input('search');

        $query = Etudiant::with(['user', 'filiere', 'groupe'])
            ->withCount([
                'absences as total_absences',
                'absences as absences_non_justifiees' => fn($q) => $q->where('justifiee', false),
                'absences as absences_justifiees' => fn($q) => $q->where('justifiee', true),
            ]);

        if ($filiere_id) {
            $query->where('filiere_id', $filiere_id);
        }

        if ($groupe_id) {
            $query->where('groupe_id', $groupe_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
            });
        }

        $etudiants = $query->orderBy('matricule')->paginate(15);
        $filieres = Filiere::all();
        $groupes = Groupe::all();

        $statsGlobales = [
            'total_absences' => Absence::count(),
            'non_justifiees' => Absence::where('justifiee', false)->count(),
            'justifiees' => Absence::where('justifiee', true)->count(),
        ];

        return view('admin.absences.index', compact(
            'etudiants', 'filieres', 'groupes', 'filiere_id', 'groupe_id', 'search', 'statsGlobales'
        ));
    }

    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['user', 'filiere', 'groupe']);
        $absences = Absence::with(['seance.module', 'justificatif'])
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->paginate(15);

        return view('admin.absences.show', compact('etudiant', 'absences'));
    }

    public function justificatifs(Request $request)
    {
        $statut = $request->input('statut');

        $query = Justificatif::with(['etudiant.user', 'absence.seance.module']);

        if ($statut) {
            $query->where('statut', $statut);
        }

        $justificatifs = $query->latest()->paginate(15);

        return view('admin.absences.justificatifs', compact('justificatifs', 'statut'));
    }

    public function valider(Justificatif $justificatif)
    {
        if ($justificatif->statut !== 'en_attente') {
            return back()->with('error', 'Ce justificatif a déjà été traité.');
        }

        DB::transaction(function () use ($justificatif) {
            $justificatif->statut = 'valide';
            $justificatif->traite_par = auth()->id();
            $justificatif->traite_le = Carbon::now();
            $justificatif->save();

            $absence = $justificatif->absence;
            if ($absence) {
                $absence->justifiee = true;
                $absence->motif = 'Justifié par certificat médical';
                $absence->save();
            }

            NotificationUpf::create([
                'user_id' => $justificatif->etudiant->user_id,
                'titre' => 'Justificatif d\'absence validé',
                'message' => 'Votre justificatif d\'absence pour la séance de ' . ($absence?->seance?->module?->nom ?? 'cours') . ' a été validé.',
                'lien' => '/etudiant/absences',
                'lu' => false,
                'type' => 'success',
            ]);
        });

        return redirect()->route('admin.absences.justificatifs')->with('success', 'Le justificatif a été validé.');
    }

    public function refuser(Request $request, Justificatif $justificatif)
    {
        if ($justificatif->statut !== 'en_attente') {
            return back()->with('error', 'Ce justificatif a déjà été traité.');
        }

        $request->validate([
            'motif_rejet' => 'required|string|max:1000',
        ]);

        DB::transaction(function () use ($request, $justificatif) {
            $justificatif->statut = 'rejete';
            $justificatif->motif_rejet = $request->motif_rejet;
            $justificatif->traite_par = auth()->id();
            $justificatif->traite_le = Carbon::now();
            $justificatif->save();

            $absence = $justificatif->absence;
            if ($absence) {
                $absence->justifiee = false;
                $absence->save();
            }

            NotificationUpf::create([
                'user_id' => $justificatif->etudiant->user_id,
                'titre' => 'Justificatif d\'absence refusé',
                'message' => 'Votre justificatif d\'absence a été refusé. Motif : ' . $request->motif_rejet,
                'lien' => '/etudiant/absences',
                'lu' => false,
                'type' => 'warning',
            ]);
        });

        return redirect()->route('admin.absences.justificatifs')->with('success', 'Le justificatif a été refusé.');
    }

    public function telecharger(Justificatif $justificatif)
    {
        if (!Storage::disk('public')->exists($justificatif->fichier_path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download($justificatif->fichier_path, $justificatif->fichier_nom);
    }

    // ─── Export liste de présence – Excel (CSV) ───────────────────────────────
    public function exportExcel(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id  = $request->input('groupe_id');

        $query = Etudiant::with(['user', 'filiere', 'groupe', 'absences.seance.module'])
            ->withCount([
                'absences as total_absences',
                'absences as absences_non_justifiees' => fn($q) => $q->where('justifiee', false),
                'absences as absences_justifiees'     => fn($q) => $q->where('justifiee', true),
            ]);

        if ($filiere_id) $query->where('filiere_id', $filiere_id);
        if ($groupe_id)  $query->where('groupe_id',  $groupe_id);

        $etudiants = $query->orderBy('matricule')->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="absences_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($etudiants) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM
            fputcsv($file, ['Matricule', 'Nom Étudiant', 'Filière', 'Groupe', 'Total Absences', 'Justifiées', 'Non Justifiées']);
            foreach ($etudiants as $e) {
                fputcsv($file, [
                    $e->matricule,
                    $e->user->name,
                    $e->filiere->code  ?? '-',
                    $e->groupe->nom    ?? '-',
                    $e->total_absences,
                    $e->absences_justifiees,
                    $e->absences_non_justifiees,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Export liste de présence – PDF ───────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id  = $request->input('groupe_id');

        $query = Etudiant::with(['user', 'filiere', 'groupe'])
            ->withCount([
                'absences as total_absences',
                'absences as absences_non_justifiees' => fn($q) => $q->where('justifiee', false),
                'absences as absences_justifiees'     => fn($q) => $q->where('justifiee', true),
            ]);

        if ($filiere_id) $query->where('filiere_id', $filiere_id);
        if ($groupe_id)  $query->where('groupe_id',  $groupe_id);

        $etudiants = $query->orderBy('matricule')->get();
        $filieres  = Filiere::all();
        $groupes   = Groupe::all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.export-absences', compact('etudiants', 'filieres', 'groupes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('liste-absences-' . now()->format('Y-m-d') . '.pdf');
    }
}
