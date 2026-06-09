<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type');
        $search = $request->input('search');

        $query = Salle::query();

        if ($type) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('batiment', 'like', "%{$search}%");
            });
        }

        $salles = $query->orderBy('nom')->paginate(15);

        return view('admin.salles.index', compact('salles', 'type', 'search'));
    }

    public function create()
    {
        return view('admin.salles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:salles,nom',
            'capacite' => 'required|integer|min:1',
            'type' => 'required|string', // e.g. amphi, td, tp
            'batiment' => 'required|string|max:255',
            'disponible' => 'boolean',
        ]);

        Salle::create([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'batiment' => $request->batiment,
            'disponible' => $request->has('disponible'),
        ]);

        return redirect()->route('admin.salles.index')->with('success', 'Salle "' . $request->nom . '" créée avec succès.');
    }

    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:salles,nom,' . $salle->id,
            'capacite' => 'required|integer|min:1',
            'type' => 'required|string',
            'batiment' => 'required|string|max:255',
            'disponible' => 'boolean',
        ]);

        $salle->update([
            'nom' => $request->nom,
            'capacite' => $request->capacite,
            'type' => $request->type,
            'batiment' => $request->batiment,
            'disponible' => $request->has('disponible'),
        ]);

        return redirect()->route('admin.salles.index')->with('success', 'Salle "' . $salle->nom . '" mise à jour avec succès.');
    }

    public function destroy(Salle $salle)
    {
        if ($salle->seances()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette salle car des séances de cours y sont planifiées.');
        }

        if ($salle->reservations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer cette salle car des réservations y sont associées.');
        }

        $nom = $salle->nom;
        $salle->delete();

        return redirect()->route('admin.salles.index')->with('success', 'Salle "' . $nom . '" supprimée avec succès.');
    }
}
