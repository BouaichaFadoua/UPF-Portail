<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === User::ROLE_ETUDIANT) {
            $etudiant = $user->etudiant;
            if (!$etudiant) return response()->json(['message' => 'Profil étudiant introuvable.'], 404);

            $modules = Module::with('professeur.user')->where('filiere_id', $etudiant->filiere_id)->get();
            return response()->json($modules);
        }

        if ($user->role === User::ROLE_PROFESSEUR) {
            $prof = $user->professeur;
            if (!$prof) return response()->json(['message' => 'Profil enseignant introuvable.'], 404);

            $modules = Module::with(['filiere', 'professeur.user'])->where('professeur_id', $prof->id)->get();
            return response()->json($modules);
        }

        if ($user->role === User::ROLE_ADMIN) {
            $modules = Module::with(['filiere', 'professeur.user'])->get();
            return response()->json($modules);
        }

        return response()->json(['message' => 'Non autorisé.'], 403);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $module = Module::with(['filiere', 'professeur.user'])->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module introuvable.'], 404);
        }

        // Check if student belongs to the filiere of the module
        if ($user->role === User::ROLE_ETUDIANT) {
            $etudiant = $user->etudiant;
            if (!$etudiant || $etudiant->filiere_id !== $module->filiere_id) {
                return response()->json(['message' => 'Accès refusé à ce module.'], 403);
            }
        }

        // Check if professor teaches the module
        if ($user->role === User::ROLE_PROFESSEUR) {
            $prof = $user->professeur;
            if (!$prof || $module->professeur_id !== $prof->id) {
                return response()->json(['message' => 'Accès refusé à ce module.'], 403);
            }
        }

        return response()->json($module);
    }
}
