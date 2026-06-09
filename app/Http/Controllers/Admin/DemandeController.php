<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use App\Models\Document;
use App\Models\NotificationUpf;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DemandeController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index(Request $request)
    {
        $statut = $request->input('statut');
        $type = $request->input('type');

        $query = Demande::with('user');

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $demandes = $query->latest()->paginate(15);

        return view('admin.demandes.index', compact('demandes', 'statut', 'type'));
    }

    public function show(Demande $demande)
    {
        $demande->load(['user', 'document']);
        return view('admin.demandes.show', compact('demande'));
    }

    public function valider(Request $request, Demande $demande)
    {
        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        try {
            $demande->statut = 'validee';
            $demande->traite_par = auth()->id();
            $demande->traite_le = Carbon::now();
            $demande->save();

            // Generate PDF
            $filePath = $this->pdfService->genererDocument($demande);

            // Save document link
            Document::create([
                'demande_id' => $demande->id,
                'fichier_path' => $filePath,
                'fichier_nom' => basename($filePath),
                'generated_at' => Carbon::now(),
            ]);

            // Notify User
            NotificationUpf::create([
                'user_id' => $demande->user_id,
                'titre' => 'Demande validée',
                'message' => 'Votre demande de ' . Demande::$typeLabels[$demande->type] . ' a été validée. Le document est prêt au téléchargement.',
                'lien' => $demande->user->isEtudiant() ? '/etudiant/demandes' : '/prof/demandes',
                'lu' => false,
                'type' => 'success',
            ]);

            return redirect()->route('admin.demandes.index')->with('success', 'La demande a été validée et le PDF a été généré.');
        } catch (\Exception $e) {
            // Rollback status
            $demande->statut = 'en_attente';
            $demande->save();

            return back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }

    public function refuser(Request $request, Demande $demande)
    {
        if ($demande->statut !== 'en_attente') {
            return back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        $demande->statut = 'refusee';
        $demande->motif_refus = $request->motif_refus;
        $demande->traite_par = auth()->id();
        $demande->traite_le = Carbon::now();
        $demande->save();

        // Notify User
        NotificationUpf::create([
            'user_id' => $demande->user_id,
            'titre' => 'Demande refusée',
            'message' => 'Votre demande de ' . Demande::$typeLabels[$demande->type] . ' a été refusée. Motif : ' . $request->motif_refus,
            'lien' => $demande->user->isEtudiant() ? '/etudiant/demandes' : '/prof/demandes',
            'lu' => false,
            'type' => 'warning',
        ]);

        return redirect()->route('admin.demandes.index')->with('success', 'La demande a été refusée.');
    }

    public function telecharger(Demande $demande)
    {
        if ($demande->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $document = $demande->document;
        if (!$document || !Storage::disk('public')->exists($document->fichier_path)) {
            abort(404, 'Fichier introuvable.');
        }

        return Storage::disk('public')->download($document->fichier_path, $document->fichier_nom);
    }
}
