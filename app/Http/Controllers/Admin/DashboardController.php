<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Personnel;
use App\Models\Filiere;
use App\Models\Salle;
use App\Models\Demande;
use App\Models\Reservation;
use App\Models\Justificatif;
use App\Models\Absence;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'etudiants_count' => Etudiant::count(),
            'professeurs_count' => Professeur::count(),
            'personnel_count' => Personnel::count(),
            'filieres_count' => Filiere::count(),
            'salles_count' => Salle::count(),
            'demandes_en_attente' => Demande::where('statut', 'en_attente')->count(),
            'reservations_en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'justificatifs_en_attente' => Justificatif::where('statut', 'en_attente')->count(),
            'total_absences' => Absence::count(),
        ];

        $recent_reservations = Reservation::with(['professeur.user', 'salle'])->latest()->take(5)->get();
        $recent_demandes = Demande::with('user')->latest()->take(5)->get();
        $recent_justificatifs = Justificatif::with(['etudiant.user', 'absence.seance.module'])->latest()->take(5)->get();

        // Statistiques Graphiques
        $absencesByModule = DB::table('absences')
            ->join('seances', 'absences.seance_id', '=', 'seances.id')
            ->join('modules', 'seances.module_id', '=', 'modules.id')
            ->select('modules.nom as module', DB::raw('count(absences.id) as total'))
            ->groupBy('modules.nom')
            ->get();

        $averagesByFiliere = DB::table('notes')
            ->join('modules', 'notes.module_id', '=', 'modules.id')
            ->join('filieres', 'modules.filiere_id', '=', 'filieres.id')
            ->select('filieres.nom as filiere', DB::raw('ROUND(avg(notes.note_finale), 2) as moyenne'))
            ->whereNotNull('notes.note_finale')
            ->groupBy('filieres.nom')
            ->get();

        $gradeDistributionRaw = DB::table('notes')
            ->select(DB::raw('note_finale'))
            ->whereNotNull('note_finale')
            ->get();
        
        $distribution = [
            '< 10' => 0,
            '10 - 11.99' => 0,
            '12 - 13.99' => 0,
            '14 - 15.99' => 0,
            '>= 16' => 0
        ];
        
        foreach($gradeDistributionRaw as $note) {
            $val = $note->note_finale;
            if($val < 10) $distribution['< 10']++;
            elseif($val < 12) $distribution['10 - 11.99']++;
            elseif($val < 14) $distribution['12 - 13.99']++;
            elseif($val < 16) $distribution['14 - 15.99']++;
            else $distribution['>= 16']++;
        }

        return view('admin.dashboard', compact(
            'stats', 'recent_reservations', 'recent_demandes', 'recent_justificatifs', 
            'absencesByModule', 'averagesByFiliere', 'distribution'
        ));
    }
}
