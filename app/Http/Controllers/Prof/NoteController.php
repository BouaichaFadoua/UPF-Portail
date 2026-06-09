<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Groupe;
use App\Models\Note;
use App\Models\Etudiant;
use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    protected $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index()
    {
        $prof = auth()->user()->professeur;
        $modules = Module::with(['filiere.groupes'])->where('professeur_id', $prof->id)->get();

        return view('prof.notes.index', compact('modules'));
    }

    public function saisir(Request $request, Module $module, Groupe $groupe)
    {
        $prof = auth()->user()->professeur;

        // Verify this professor teaches this module
        if ($module->professeur_id !== $prof->id) {
            abort(403, 'Vous n\'êtes pas l\'enseignant de ce module.');
        }

        // Verify the group belongs to the module's filiere
        if ($groupe->filiere_id !== $module->filiere_id) {
            abort(400, 'Ce groupe n\'appartient pas à la filière de ce module.');
        }

        // Fetch students in this group
        $etudiants = Etudiant::with(['user', 'notes' => function($query) use ($module) {
            $query->where('module_id', $module->id);
        }])->where('groupe_id', $groupe->id)->get();

        return view('prof.notes.edit', compact('module', 'groupe', 'etudiants'));
    }

    public function enregistrer(Request $request, Module $module, Groupe $groupe)
    {
        $prof = auth()->user()->professeur;

        if ($module->professeur_id !== $prof->id) {
            abort(403, 'Accès interdit.');
        }

        $request->validate([
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:etudiants,id',
            'notes.*.cc1' => 'nullable|numeric|min:0|max:20',
            'notes.*.cc2' => 'nullable|numeric|min:0|max:20',
            'notes.*.examen' => 'nullable|numeric|min:0|max:20',
        ]);

        DB::transaction(function() use ($request, $module) {
            foreach ($request->notes as $noteData) {
                $this->noteService->sauvegarderNote($noteData['etudiant_id'], $module->id, [
                    'cc1' => $noteData['cc1'],
                    'cc2' => $noteData['cc2'],
                    'examen' => $noteData['examen'],
                    'annee_universitaire' => '2024-2025',
                ]);
            }
        });

        return redirect()->route('prof.notes.saisir', [$module->id, $groupe->id])
            ->with('success', 'Les notes ont été enregistrées avec succès.');
    }
}
