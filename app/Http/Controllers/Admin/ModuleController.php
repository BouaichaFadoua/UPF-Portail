<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Filiere;
use App\Models\Professeur;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $search = $request->input('search');

        $query = Module::with(['filiere', 'professeur.user']);

        if ($filiere_id) {
            $query->where('filiere_id', $filiere_id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $modules = $query->orderBy('code')->paginate(15);
        $filieres = Filiere::all();

        return view('admin.modules.index', compact('modules', 'filieres', 'filiere_id', 'search'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $professeurs = Professeur::with('user')->get();
        return view('admin.modules.create', compact('filieres', 'professeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'professeur_id' => 'nullable|exists:professeurs,id',
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:modules,code',
            'coefficient' => 'required|numeric|min:0.5',
            'semestre' => 'required|integer|in:1,2',
            'volume_horaire' => 'required|integer|min:1',
        ]);

        Module::create($request->only([
            'filiere_id', 'professeur_id', 'nom', 'code', 'coefficient', 'semestre', 'volume_horaire'
        ]));

        return redirect()->route('admin.modules.index')->with('success', 'Module "' . $request->nom . '" créé avec succès.');
    }

    public function edit(Module $module)
    {
        $filieres = Filiere::all();
        $professeurs = Professeur::with('user')->get();
        return view('admin.modules.edit', compact('module', 'filieres', 'professeurs'));
    }

    public function update(Request $request, Module $module)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'professeur_id' => 'nullable|exists:professeurs,id',
            'nom' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:modules,code,' . $module->id,
            'coefficient' => 'required|numeric|min:0.5',
            'semestre' => 'required|integer|in:1,2',
            'volume_horaire' => 'required|integer|min:1',
        ]);

        $module->update($request->only([
            'filiere_id', 'professeur_id', 'nom', 'code', 'coefficient', 'semestre', 'volume_horaire'
        ]));

        return redirect()->route('admin.modules.index')->with('success', 'Module "' . $module->nom . '" mis à jour avec succès.');
    }

    public function destroy(Module $module)
    {
        if ($module->notes()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce module car des notes y sont associées.');
        }

        if ($module->seances()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce module car des séances de cours sont planifiées pour celui-ci.');
        }

        $nom = $module->nom;
        $module->delete();

        return redirect()->route('admin.modules.index')->with('success', 'Module "' . $nom . '" supprimé avec succès.');
    }
}
