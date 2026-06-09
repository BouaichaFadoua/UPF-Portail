<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\CahierTexte;
use App\Models\Seance;
use App\Models\Module;
use Illuminate\Http\Request;

class CahierTexteController extends Controller
{
    public function index()
    {
        $prof = auth()->user()->professeur;
        $cahiers = CahierTexte::with(['module', 'seance'])
            ->where('professeur_id', $prof->id)
            ->latest('date')
            ->paginate(15);

        return view('prof.cahier-textes.index', compact('cahiers'));
    }

    public function create()
    {
        $prof = auth()->user()->professeur;
        
        // Find seances of this professor that don't have a cahier de texte entry yet
        $seances = Seance::with(['module', 'groupe'])
            ->where('professeur_id', $prof->id)
            ->whereNotExists(function($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('cahier_textes')
                    ->whereColumn('cahier_textes.seance_id', 'seances.id');
            })
            ->orderBy('date', 'desc')
            ->get();

        $modules = Module::where('professeur_id', $prof->id)->get();

        return view('prof.cahier-textes.create', compact('seances', 'modules'));
    }

    public function store(Request $request)
    {
        $prof = auth()->user()->professeur;

        $request->validate([
            'seance_id' => 'nullable|exists:seances,id',
            'module_id' => 'required_without:seance_id|nullable|exists:modules,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'nature' => 'required|in:Cours,TD,TP',
            'objectif' => 'required|string|max:1000',
            'contenu' => 'nullable|string',
        ]);

        $seance = null;
        $moduleId = $request->module_id;

        if ($request->filled('seance_id')) {
            $seance = Seance::find($request->seance_id);
            if ($seance->professeur_id !== $prof->id) {
                abort(403);
            }
            $moduleId = $seance->module_id;
        }

        CahierTexte::create([
            'professeur_id' => $prof->id,
            'module_id' => $moduleId,
            'seance_id' => $seance?->id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut . ':00',
            'heure_fin' => $request->heure_fin . ':00',
            'objectif' => $request->objectif,
            'nature' => $request->nature,
            'contenu' => $request->contenu,
        ]);

        return redirect()->route('prof.cahier-textes.index')
            ->with('success', 'Entrée ajoutée au cahier de textes avec succès.');
    }
}
