<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Afficher le formulaire de modification du mot de passe
     */
    public function showPasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Traiter la modification du mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'current_password.required'      => 'Le mot de passe actuel est obligatoire.',
            'password.required'              => 'Le nouveau mot de passe est obligatoire.',
            'password.min'                   => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'             => 'La confirmation du mot de passe ne correspond pas.',
            'password_confirmation.required' => 'Veuillez confirmer votre nouveau mot de passe.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Redirect based on role
        $route = match(true) {
            $user->isAdmin()      => 'admin.dashboard',
            $user->isProfesseur() => 'prof.dashboard',
            $user->isEtudiant()   => 'etudiant.dashboard',
            default               => 'login',
        };

        return redirect()->route($route)
            ->with('success', 'Mot de passe modifié avec succès !');
    }
}
