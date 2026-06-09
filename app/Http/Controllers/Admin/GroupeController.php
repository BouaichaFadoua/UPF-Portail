<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\Filiere;
use Illuminate\Http\Request;

class GroupeController extends Controller
{
    public function index(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $search = $request->input('search');

        $query = Groupe::with('filiere')->withCount('etudiants');

        if ($filiere_id) {
            $query->where('filiere_id', $filiere_id);
        }

        if ($search) {
            $query->where('nom', 'like', "%{$search}%");
        }

        $groupes = $query->orderBy('nom')->paginate(15);
        $filieres = Filiere::all();

        return view('admin.groupes.index', compact('groupes', 'filieres', 'filiere_id', 'search'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        return view('admin.groupes.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
        ]);

        Groupe::create($request->only(['filiere_id', 'nom', 'capacite']));

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe "' . $request->nom . '" créé avec succès.');
    }

    public function edit(Groupe $groupe)
    {
        $filieres = Filiere::all();
        return view('admin.groupes.edit', compact('groupe', 'filieres'));
    }

    public function update(Request $request, Groupe $groupe)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
        ]);

        $groupe->update($request->only(['filiere_id', 'nom', 'capacite']));

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe "' . $groupe->nom . '" mis à jour avec succès.');
    }

    public function destroy(Groupe $groupe)
    {
        if ($groupe->etudiants()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce groupe car des étudiants y sont affectés.');
        }

        if ($groupe->seances()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer ce groupe car des séances de cours y sont planifiées.');
        }

        $nom = $groupe->nom;
        $groupe->delete();

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe "' . $nom . '" supprimé avec succès.');
    }
}
