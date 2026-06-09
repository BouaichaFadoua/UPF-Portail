<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $week = $request->input('week', Carbon::now()->weekOfYear);

        if ($user->role === User::ROLE_ETUDIANT) {
            $etudiant = $user->etudiant;
            if (!$etudiant) return response()->json(['message' => 'Profil étudiant introuvable.'], 404);

            $seances = Seance::with(['module', 'salle', 'professeur.user'])
                ->where('groupe_id', $etudiant->groupe_id)
                ->where('semaine', $week)
                ->get();
            return response()->json($seances);
        }

        if ($user->role === User::ROLE_PROFESSEUR) {
            $prof = $user->professeur;
            if (!$prof) return response()->json(['message' => 'Profil enseignant introuvable.'], 404);

            $seances = Seance::with(['module', 'salle', 'groupe'])
                ->where('professeur_id', $prof->id)
                ->where('semaine', $week)
                ->get();
            return response()->json($seances);
        }

        if ($user->role === User::ROLE_ADMIN) {
            $seances = Seance::with(['module', 'salle', 'groupe', 'professeur.user'])
                ->where('semaine', $week)
                ->get();
            return response()->json($seances);
        }

        return response()->json(['message' => 'Non autorisé.'], 403);
    }
}
