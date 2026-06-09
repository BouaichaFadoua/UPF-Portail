<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Professeur;
use App\Models\Personnel;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->input('role');
        $search = $request->input('search');

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users', 'role', 'search'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        return view('admin.users.create', compact('filieres', 'groupes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,professeur,etudiant,personnel',
            'actif' => 'boolean',
            // Student validation
            'matricule' => 'required_if:role,etudiant|nullable|string|unique:etudiants,matricule',
            'filiere_id' => 'required_if:role,etudiant|nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            // Prof validation
            'grade' => 'required_if:role,professeur|nullable|string',
            'specialite' => 'required_if:role,professeur|nullable|string',
            'bureau' => 'nullable|string',
            // Personnel validation
            'poste' => 'required_if:role,personnel|nullable|string',
            'service' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'actif' => $request->has('actif'),
            ]);

            if ($user->role === User::ROLE_ETUDIANT) {
                Etudiant::create([
                    'user_id' => $user->id,
                    'filiere_id' => $request->filiere_id,
                    'groupe_id' => $request->groupe_id,
                    'matricule' => $request->matricule,
                    'date_naissance' => $request->date_naissance,
                    'telephone' => $request->telephone,
                    'adresse' => $request->adresse,
                ]);
            } elseif ($user->role === User::ROLE_PROFESSEUR) {
                Professeur::create([
                    'user_id' => $user->id,
                    'grade' => $request->grade,
                    'specialite' => $request->specialite,
                    'telephone' => $request->telephone,
                    'bureau' => $request->bureau,
                ]);
            } elseif ($user->role === User::ROLE_PERSONNEL) {
                Personnel::create([
                    'user_id' => $user->id,
                    'poste' => $request->poste,
                    'service' => $request->service,
                    'telephone' => $request->telephone,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function edit(User $user)
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $user->load(['etudiant', 'professeur', 'personnel']);
        return view('admin.users.edit', compact('user', 'filieres', 'groupes'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'actif' => 'boolean',
            // Student validation
            'matricule' => 'required_if:role,etudiant|nullable|string|unique:etudiants,matricule,' . ($user->etudiant?->id ?? 'NULL'),
            'filiere_id' => 'required_if:role,etudiant|nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'date_naissance' => 'nullable|date',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            // Prof validation
            'grade' => 'required_if:role,professeur|nullable|string',
            'specialite' => 'required_if:role,professeur|nullable|string',
            'bureau' => 'nullable|string',
            // Personnel validation
            'poste' => 'required_if:role,personnel|nullable|string',
            'service' => 'nullable|string',
        ]);

        DB::transaction(function() use ($request, $user) {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->actif = $request->has('actif');
            $user->save();

            if ($user->role === User::ROLE_ETUDIANT) {
                $etudiant = Etudiant::firstOrNew(['user_id' => $user->id]);
                $etudiant->filiere_id = $request->filiere_id;
                $etudiant->groupe_id = $request->groupe_id;
                $etudiant->matricule = $request->matricule;
                $etudiant->date_naissance = $request->date_naissance;
                $etudiant->telephone = $request->telephone;
                $etudiant->adresse = $request->adresse;
                $etudiant->save();
            } elseif ($user->role === User::ROLE_PROFESSEUR) {
                $professeur = Professeur::firstOrNew(['user_id' => $user->id]);
                $professeur->grade = $request->grade;
                $professeur->specialite = $request->specialite;
                $professeur->telephone = $request->telephone;
                $professeur->bureau = $request->bureau;
                $professeur->save();
            } elseif ($user->role === User::ROLE_PERSONNEL) {
                $personnel = Personnel::firstOrNew(['user_id' => $user->id]);
                $personnel->poste = $request->poste;
                $personnel->service = $request->service;
                $personnel->telephone = $request->telephone;
                $personnel->save();
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas supprimer votre propre compte.']);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas modifier votre propre statut.']);
        }
        $user->actif = !$user->actif;
        $user->save();

        $status = $user->actif ? 'activé' : 'désactivé';
        return back()->with('success', "Le compte de {$user->name} a été {$status} avec succès.");
    }
}
