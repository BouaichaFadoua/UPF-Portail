<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function index()
    {
        $prof = auth()->user()->professeur;
        $reservations = Reservation::with('salle')
            ->where('professeur_id', $prof->id)
            ->latest()
            ->paginate(15);

        return view('prof.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $salles = Salle::where('disponible', true)->get();
        return view('prof.reservations.create', compact('salles'));
    }

    public function sallesDisponibles(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        $salles = $this->reservationService->getSallesDisponibles(
            $request->date,
            $request->heure_debut . ':00',
            $request->heure_fin . ':00'
        );

        return response()->json($salles);
    }

    public function store(Request $request)
    {
        $prof = auth()->user()->professeur;

        $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'motif' => 'required|string|max:255',
        ]);

        $dateStr = $request->date;
        $debut = $request->heure_debut . ':00';
        $fin = $request->heure_fin . ':00';

        // Check Conflict
        $conflit = $this->reservationService->verifierConflit($request->salle_id, $dateStr, $debut, $fin);

        if ($conflit) {
            return back()->withErrors(['salle_id' => 'La salle est déjà réservée ou demandée sur ce créneau.'])->withInput();
        }

        Reservation::create([
            'professeur_id' => $prof->id,
            'salle_id' => $request->salle_id,
            'date' => $request->date,
            'heure_debut' => $debut,
            'heure_fin' => $fin,
            'motif' => $request->motif,
            'statut' => 'en_attente',
        ]);

        return redirect()->route('prof.reservations.index')
            ->with('success', 'Votre demande de réservation a été soumise avec succès.');
    }

    public function annuler(Reservation $reservation)
    {
        $prof = auth()->user()->professeur;
        if ($reservation->professeur_id !== $prof->id) {
            abort(403);
        }

        if ($reservation->statut === 'approuvee') {
            $reservation->statut = 'annulee';
            $reservation->save();
            return back()->with('success', 'Réservation annulée.');
        }

        if ($reservation->statut === 'en_attente') {
            $reservation->delete();
            return back()->with('success', 'Demande de réservation retirée.');
        }

        return back()->with('error', 'Impossible d\'annuler cette réservation.');
    }
}
