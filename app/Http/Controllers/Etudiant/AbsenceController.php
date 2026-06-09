<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Justificatif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AbsenceController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $absences = Absence::with(['seance.module', 'justificatif'])
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->paginate(15);

        // Get absences summary by module
        $absencesParModule = Absence::select('modules.nom as module_nom', 'modules.code as module_code')
            ->selectRaw('count(absences.id) as total')
            ->selectRaw('sum(case when absences.justifiee = 1 then 1 else 0 end) as justifiees')
            ->selectRaw('sum(case when absences.justifiee = 0 then 1 else 0 end) as non_justifiees')
            ->join('seances', 'absences.seance_id', '=', 'seances.id')
            ->join('modules', 'seances.module_id', '=', 'modules.id')
            ->where('absences.etudiant_id', $etudiant->id)
            ->groupBy('modules.id', 'modules.nom', 'modules.code')
            ->get();

        return view('etudiant.absences.index', compact('absences', 'absencesParModule'));
    }

    public function justifierForm(Absence $absence)
    {
        $etudiant = auth()->user()->etudiant;
        if ($absence->etudiant_id !== $etudiant->id) {
            abort(403);
        }

        if ($absence->justifiee || $absence->justificatif) {
            return redirect()->route('etudiant.absences.index')->with('error', 'Cette absence a déjà fait l\'objet d\'une justification.');
        }

        return view('etudiant.absences.justificatif', compact('absence'));
    }

    public function justifier(Request $request, Absence $absence)
    {
        $etudiant = auth()->user()->etudiant;
        if ($absence->etudiant_id !== $etudiant->id) {
            abort(403);
        }

        $request->validate([
            'fichier' => 'required|file|mimes:pdf,png,jpg,jpeg|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $originalName = $file->getClientOriginalName();
            
            // Save to public storage under justificatifs/
            $path = $file->store('justificatifs', 'public');

            Justificatif::create([
                'absence_id' => $absence->id,
                'etudiant_id' => $etudiant->id,
                'fichier_path' => $path,
                'fichier_nom' => $originalName,
                'statut' => 'en_attente',
            ]);

            return redirect()->route('etudiant.absences.index')
                ->with('success', 'Votre justificatif a été soumis à l\'administration avec succès.');
        }

        return back()->with('error', 'Fichier manquant.');
    }
}
