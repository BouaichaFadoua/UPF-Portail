<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id = $request->input('groupe_id');
        $module_id = $request->input('module_id');
        $search = $request->input('search');

        $query = Note::with(['etudiant.user', 'etudiant.filiere', 'etudiant.groupe', 'module']);

        if ($filiere_id) {
            $query->whereHas('etudiant', function($q) use ($filiere_id) {
                $q->where('filiere_id', $filiere_id);
            });
        }

        if ($groupe_id) {
            $query->whereHas('etudiant', function($q) use ($groupe_id) {
                $q->where('groupe_id', $groupe_id);
            });
        }

        if ($module_id) {
            $query->where('module_id', $module_id);
        }

        if ($search) {
            $query->whereHas('etudiant.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $notes = $query->latest()->paginate(15);

        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $modules = Module::all();

        return view('admin.notes.index', compact('notes', 'filieres', 'groupes', 'modules', 'filiere_id', 'groupe_id', 'module_id', 'search'));
    }

    public function create()
    {
        $etudiants = Etudiant::with('user')->get();
        $modules = Module::all();
        return view('admin.notes.create', compact('etudiants', 'modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'module_id' => 'required|exists:modules,id',
            'cc1' => 'nullable|numeric|min:0|max:20',
            'cc2' => 'nullable|numeric|min:0|max:20',
            'examen' => 'nullable|numeric|min:0|max:20',
            'annee_universitaire' => 'required|string',
        ]);

        // Check unique constraint
        $exists = Note::where('etudiant_id', $request->etudiant_id)
            ->where('module_id', $request->module_id)
            ->where('annee_universitaire', $request->annee_universitaire)
            ->exists();

        if ($exists) {
            return back()->withErrors(['etudiant_id' => 'Une note existe déjà pour cet étudiant et ce module pour cette année universitaire.'])->withInput();
        }

        Note::create($request->only([
            'etudiant_id', 'module_id', 'cc1', 'cc2', 'examen', 'annee_universitaire'
        ]));

        return redirect()->route('admin.notes.index')->with('success', 'Note ajoutée avec succès.');
    }

    public function edit(Note $note)
    {
        $etudiants = Etudiant::with('user')->get();
        $modules = Module::all();
        return view('admin.notes.edit', compact('note', 'etudiants', 'modules'));
    }

    public function update(Request $request, Note $note)
    {
        $request->validate([
            'cc1' => 'nullable|numeric|min:0|max:20',
            'cc2' => 'nullable|numeric|min:0|max:20',
            'examen' => 'nullable|numeric|min:0|max:20',
            'annee_universitaire' => 'required|string',
        ]);

        $note->update($request->only([
            'cc1', 'cc2', 'examen', 'annee_universitaire'
        ]));

        return redirect()->route('admin.notes.index')->with('success', 'Note mise à jour avec succès.');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('admin.notes.index')->with('success', 'Note supprimée avec succès.');
    }

    public function export(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id = $request->input('groupe_id');
        $module_id = $request->input('module_id');

        $query = Note::with(['etudiant.user', 'etudiant.filiere', 'etudiant.groupe', 'module']);

        if ($filiere_id) {
            $query->whereHas('etudiant', function($q) use ($filiere_id) {
                $q->where('filiere_id', $filiere_id);
            });
        }

        if ($groupe_id) {
            $query->whereHas('etudiant', function($q) use ($groupe_id) {
                $q->where('groupe_id', $groupe_id);
            });
        }

        if ($module_id) {
            $query->where('module_id', $module_id);
        }

        $notes = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="notes_export_' . now()->format('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($notes) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, ['Matricule', 'Nom Étudiant', 'Filière', 'Groupe', 'Module', 'CC1', 'CC2', 'Examen', 'Note Finale', 'Mention', 'Année']);

            foreach ($notes as $note) {
                fputcsv($file, [
                    $note->etudiant->matricule,
                    $note->etudiant->user->name,
                    $note->etudiant->filiere->code,
                    $note->etudiant->groupe?->nom ?? 'N/A',
                    $note->module->nom,
                    $note->cc1 ?? 'N/A',
                    $note->cc2 ?? 'N/A',
                    $note->examen ?? 'N/A',
                    $note->note_finale ?? 'N/A',
                    $note->mention,
                    $note->annee_universitaire
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─── Export Excel (XLSX-compatible CSV with BOM) ─────────────────────────
    public function exportExcel(Request $request)
    {
        return $this->export($request); // reuse CSV export (Excel opens it natively)
    }

    // ─── Export PDF ───────────────────────────────────────────────────────────
    public function exportPdf(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id  = $request->input('groupe_id');
        $module_id  = $request->input('module_id');

        $query = Note::with(['etudiant.user', 'etudiant.filiere', 'etudiant.groupe', 'module']);

        if ($filiere_id) {
            $query->whereHas('etudiant', fn($q) => $q->where('filiere_id', $filiere_id));
        }
        if ($groupe_id) {
            $query->whereHas('etudiant', fn($q) => $q->where('groupe_id', $groupe_id));
        }
        if ($module_id) {
            $query->where('module_id', $module_id);
        }

        $notes    = $query->get();
        $filieres = Filiere::all();
        $groupes  = Groupe::all();
        $modules  = Module::all();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.export-notes', compact('notes', 'filieres', 'groupes', 'modules'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('releve-notes-' . now()->format('Y-m-d') . '.pdf');
    }
}
