<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Annonce;
use App\Models\Support;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoursController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        // Find modules belonging to the student's filiere
        $modules = Module::with(['professeur.user'])
            ->where('filiere_id', $etudiant->filiere_id)
            ->get();

        return view('etudiant.cours.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $etudiant = auth()->user()->etudiant;
        
        if ($module->filiere_id !== $etudiant->filiere_id) {
            abort(403, 'Ce module ne fait pas partie de votre filière.');
        }

        $annonces = Annonce::with(['professeur.user', 'commentaires.user'])
            ->where('module_id', $module->id)
            ->latest()
            ->get();

        $supports = Support::where('module_id', $module->id)->latest()->get();

        return view('etudiant.cours.classroom', compact('module', 'annonces', 'supports'));
    }

    public function commenter(Request $request, Annonce $annonce)
    {
        $request->validate([
            'contenu' => 'required|string|max:1000',
        ]);

        Commentaire::create([
            'annonce_id' => $annonce->id,
            'user_id' => auth()->id(),
            'contenu' => $request->contenu,
        ]);

        return back()->with('success', 'Votre commentaire a été posté.');
    }

    public function telechargerSupport(Support $support)
    {
        $etudiant = auth()->user()->etudiant;

        if ($support->module->filiere_id !== $etudiant->filiere_id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($support->fichier_path)) {
            abort(404, 'Fichier introuvable sur le serveur.');
        }

        return Storage::disk('public')->download($support->fichier_path, $support->fichier_nom);
    }
}
