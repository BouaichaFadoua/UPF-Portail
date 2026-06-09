<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\Absence;
use App\Models\Note;
use App\Models\NotificationUpf;
use App\Services\NoteService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;

        if (!$etudiant) {
            abort(403, 'Profil étudiant introuvable.');
        }

        $noteService = new NoteService();
        $moyenne = $noteService->getMoyenneGenerale($etudiant);

        $totalAbsences = Absence::where('etudiant_id', $etudiant->id)->count();
        $absencesNonJustifiees = Absence::where('etudiant_id', $etudiant->id)->where('justifiee', false)->count();

        $stats = [
            'moyenne_generale' => $moyenne !== null ? number_format($moyenne, 2) . ' / 20' : 'N/A',
            'total_absences' => $totalAbsences,
            'absences_non_justifiees' => $absencesNonJustifiees,
            'notes_count' => Note::where('etudiant_id', $etudiant->id)->count(),
        ];

        // Today's classes
        $today_classes = Seance::with(['module', 'salle', 'professeur.user'])
            ->where('groupe_id', $etudiant->groupe_id)
            ->where('date', Carbon::today()->format('Y-m-d'))
            ->orderBy('heure_debut')
            ->get();

        // Recent notifications
        $notifications = NotificationUpf::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('etudiant.dashboard', compact('stats', 'today_classes', 'notifications'));
    }
}
