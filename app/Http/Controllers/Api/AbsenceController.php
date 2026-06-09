<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_ETUDIANT) {
            $etudiant = $user->etudiant;
            if (!$etudiant) return response()->json(['message' => 'Profil étudiant introuvable.'], 404);

            $absences = Absence::with(['seance.module', 'justificatif'])->where('etudiant_id', $etudiant->id)->get();
            return response()->json($absences);
        }

        if ($user->role === User::ROLE_PROFESSEUR) {
            $prof = $user->professeur;
            if (!$prof) return response()->json(['message' => 'Profil enseignant introuvable.'], 404);

            $absences = Absence::with(['etudiant.user', 'seance.module'])
                ->whereHas('seance', function($query) use ($prof) {
                    $query->where('professeur_id', $prof->id);
                })->get();
            return response()->json($absences);
        }

        if ($user->role === User::ROLE_ADMIN) {
            $absences = Absence::with(['etudiant.user', 'seance.module', 'justificatif'])->get();
            return response()->json($absences);
        }

        return response()->json(['message' => 'Non autorisé.'], 403);
    }
}
