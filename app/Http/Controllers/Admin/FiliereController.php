<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::withCount(['modules', 'groupes', 'etudiants'])
            ->orderBy('nom')
            ->get();

        return view('admin.filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('admin.filieres.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:filieres,code',
            'niveau'      => 'required|in:L1,L2,L3,M1,M2',
            'departement' => 'nullable|string|max:255',
        ]);

        Filiere::create($request->only(['nom', 'code', 'niveau', 'departement']));

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière "' . $request->nom . '" créée avec succès.');
    }

    public function edit(Filiere $filiere)
    {
        $filiere->loadCount(['modules', 'groupes', 'etudiants']);
        $groupes = Groupe::where('filiere_id', $filiere->id)->withCount('etudiants')->get();

        return view('admin.filieres.edit', compact('filiere', 'groupes'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|max:20|unique:filieres,code,' . $filiere->id,
            'niveau'      => 'required|in:L1,L2,L3,M1,M2',
            'departement' => 'nullable|string|max:255',
        ]);

        $filiere->update($request->only(['nom', 'code', 'niveau', 'departement']));

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière "' . $filiere->nom . '" mise à jour avec succès.');
    }

    public function destroy(Filiere $filiere)
    {
        // Sécurité : empêcher la suppression si des étudiants sont inscrits
        if ($filiere->etudiants()->count() > 0) {
            return back()->with('error',
                'Impossible de supprimer la filière "' . $filiere->nom .
                '" : ' . $filiere->etudiants()->count() . ' étudiant(s) sont encore inscrits.'
            );
        }

        $nom = $filiere->nom;
        $filiere->delete();

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière "' . $nom . '" supprimée avec succès.');
    }
}
