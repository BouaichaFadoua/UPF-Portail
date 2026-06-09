<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Seance;
use App\Models\Reservation;
use App\Models\CahierTexte;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $prof = auth()->user()->professeur;

        if (!$prof) {
            abort(403, 'Profil enseignant introuvable.');
        }

        $stats = [
            'modules_count' => Module::where('professeur_id', $prof->id)->count(),
            'seances_count' => Seance::where('professeur_id', $prof->id)->count(),
            'reservations_count' => Reservation::where('professeur_id', $prof->id)->count(),
            'cahier_textes_count' => CahierTexte::where('professeur_id', $prof->id)->count(),
        ];

        // Timetable for today
        $today_classes = Seance::with(['module', 'groupe', 'salle'])
            ->where('professeur_id', $prof->id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->orderBy('heure_debut')
            ->get();

        // Pending reservations
        $pending_reservations = Reservation::with('salle')
            ->where('professeur_id', $prof->id)
            ->where('statut', 'en_attente')
            ->latest()
            ->get();

        return view('prof.dashboard', compact('stats', 'today_classes', 'pending_reservations'));
    }
}
