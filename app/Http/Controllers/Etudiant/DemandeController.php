<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Demande;
use Illuminate\Http\Request;

class DemandeController extends Controller
{
    public function index()
    {
        $demandes = Demande::with('document')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('etudiant.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('etudiant.demandes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:attestation_scolarite,certificat_inscription,releve_notes',
        ]);

        Demande::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('etudiant.demandes.index')
            ->with('success', 'Votre demande de document administratif a été soumise avec succès.');
    }
}
