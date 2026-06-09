<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_ETUDIANT) {
            $etudiant = $user->etudiant;
            if (!$etudiant) return response()->json(['message' => 'Profil étudiant introuvable.'], 404);

            $notes = Note::with('module')->where('etudiant_id', $etudiant->id)->get();
            return response()->json($notes);
        }

        if ($user->role === User::ROLE_PROFESSEUR) {
            $prof = $user->professeur;
            if (!$prof) return response()->json(['message' => 'Profil enseignant introuvable.'], 404);

            $notes = Note::with(['etudiant.user', 'module'])
                ->whereHas('module', function($query) use ($prof) {
                    $query->where('professeur_id', $prof->id);
                })->get();
            return response()->json($notes);
        }

        if ($user->role === User::ROLE_ADMIN) {
            $notes = Note::with(['etudiant.user', 'module'])->get();
            return response()->json($notes);
        }

        return response()->json(['message' => 'Rôle non autorisé.'], 403);
    }
}
