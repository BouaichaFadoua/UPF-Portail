<?php
namespace App\Http\Controllers\Prof;

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

        return view('prof.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('prof.demandes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:attestation_travail,ordre_mission',
            // Fields for ordre_mission
            'destination' => 'required_if:type,ordre_mission|nullable|string|max:255',
            'date_depart' => 'required_if:type,ordre_mission|nullable|date|after_or_equal:today',
            'date_retour' => 'required_if:type,ordre_mission|nullable|date|after:date_depart',
            'motif_mission' => 'required_if:type,ordre_mission|nullable|string|max:1000',
        ]);

        Demande::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'statut' => 'en_attente',
            'destination' => $request->type === 'ordre_mission' ? $request->destination : null,
            'date_depart' => $request->type === 'ordre_mission' ? $request->date_depart : null,
            'date_retour' => $request->type === 'ordre_mission' ? $request->date_retour : null,
            'motif_mission' => $request->type === 'ordre_mission' ? $request->motif_mission : null,
        ]);

        return redirect()->route('prof.demandes.index')
            ->with('success', 'Votre demande de document administratif a été soumise.');
    }
}
