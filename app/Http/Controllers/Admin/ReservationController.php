<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salle;
use App\Models\Professeur;
use App\Models\NotificationUpf;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(protected ReservationService $reservationService) {}

    public function index(Request $request)
    {
        $statut = $request->input('statut');

        $query = Reservation::with(['professeur.user', 'salle']);

        if ($statut) {
            $query->where('statut', $statut);
        }

        $reservations = $query->latest()->paginate(15);

        return view('admin.reservations.index', compact('reservations', 'statut'));
    }

    public function create()
    {
        $salles = Salle::where('disponible', true)->get();
        $professeurs = Professeur::with('user')->get();
        return view('admin.reservations.create', compact('salles', 'professeurs'));
    }

    public function store(Request $request)
    {
        $data = $this->validateReservation($request);
        $debut = $data['heure_debut'];
        $fin = $data['heure_fin'];

        if ($this->reservationService->verifierConflit($data['salle_id'], $data['date'], $debut, $fin)) {
            return back()->withErrors(['salle_id' => 'La salle est déjà réservée sur ce créneau.'])->withInput();
        }

        Reservation::create(array_merge($data, [
            'statut' => $request->input('statut', 'approuvee'),
            'traite_par' => auth()->id(),
        ]));

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation créée avec succès.');
    }

    public function edit(Reservation $reservation)
    {
        $salles = Salle::where('disponible', true)->get();
        $professeurs = Professeur::with('user')->get();
        return view('admin.reservations.edit', compact('reservation', 'salles', 'professeurs'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $this->validateReservation($request);
        $debut = $data['heure_debut'];
        $fin = $data['heure_fin'];

        if ($this->reservationService->verifierConflit($data['salle_id'], $data['date'], $debut, $fin, $reservation->id)) {
            return back()->withErrors(['salle_id' => 'La salle est déjà réservée sur ce créneau.'])->withInput();
        }

        $reservation->update(array_merge($data, [
            'statut' => $request->input('statut', $reservation->statut),
            'traite_par' => auth()->id(),
        ]));

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation modifiée avec succès.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Réservation supprimée.');
    }

    public function approuver(Reservation $reservation)
    {
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation a déjà été traitée.');
        }

        $conflit = $this->reservationService->verifierConflit(
            $reservation->salle_id,
            $reservation->date->format('Y-m-d'),
            $reservation->heure_debut,
            $reservation->heure_fin,
            $reservation->id
        );

        if ($conflit) {
            return back()->with('error', 'Impossible d\'approuver. Un conflit de réservation existe pour cette salle sur ce créneau.');
        }

        $reservation->statut = 'approuvee';
        $reservation->traite_par = auth()->id();
        $reservation->save();

        NotificationUpf::create([
            'user_id' => $reservation->professeur->user_id,
            'titre' => 'Réservation de salle acceptée',
            'message' => 'Votre demande de réservation pour la salle ' . $reservation->salle->nom . ' le ' . $reservation->date->format('d/m/Y') . ' a été approuvée.',
            'lien' => '/prof/reservations',
            'lu' => false,
            'type' => 'success',
        ]);

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation approuvée.');
    }

    public function refuser(Request $request, Reservation $reservation)
    {
        if ($reservation->statut !== 'en_attente') {
            return back()->with('error', 'Cette réservation a déjà été traitée.');
        }

        $request->validate([
            'motif_refus' => 'required|string|max:1000',
        ]);

        $reservation->statut = 'refusee';
        $reservation->motif_refus = $request->motif_refus;
        $reservation->traite_par = auth()->id();
        $reservation->save();

        NotificationUpf::create([
            'user_id' => $reservation->professeur->user_id,
            'titre' => 'Réservation de salle refusée',
            'message' => 'Votre demande de réservation pour la salle ' . $reservation->salle->nom . ' le ' . $reservation->date->format('d/m/Y') . ' a été refusée. Motif : ' . $request->motif_refus,
            'lien' => '/prof/reservations',
            'lu' => false,
            'type' => 'warning',
        ]);

        return redirect()->route('admin.reservations.index')->with('success', 'Réservation refusée.');
    }

    private function validateReservation(Request $request): array
    {
        $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
            'salle_id' => 'required|exists:salles,id',
            'date' => 'required|date',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'motif' => 'required|string|max:255',
            'statut' => 'nullable|in:en_attente,approuvee,refusee,annulee',
        ]);

        return [
            'professeur_id' => $request->professeur_id,
            'salle_id' => $request->salle_id,
            'date' => $request->date,
            'heure_debut' => $request->heure_debut . ':00',
            'heure_fin' => $request->heure_fin . ':00',
            'motif' => $request->motif,
        ];
    }
}
