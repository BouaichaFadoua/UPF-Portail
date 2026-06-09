<?php
namespace App\Http\Controllers\Prof;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Annonce;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoursController extends Controller
{
    public function index()
    {
        $prof = auth()->user()->professeur;
        $modules = Module::withCount(['annonces', 'supports'])->where('professeur_id', $prof->id)->get();
        return view('prof.cours.index', compact('modules'));
    }

    public function show(Module $module)
    {
        $prof = auth()->user()->professeur;
        if ($module->professeur_id !== $prof->id) {
            abort(403);
        }

        $annonces = Annonce::with(['commentaires.user'])->where('module_id', $module->id)->latest()->get();
        $supports = Support::where('module_id', $module->id)->latest()->get();

        return view('prof.cours.classroom', compact('module', 'annonces', 'supports'));
    }

    public function posterAnnonce(Request $request, Module $module)
    {
        $prof = auth()->user()->professeur;
        if ($module->professeur_id !== $prof->id) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);

        Annonce::create([
            'module_id' => $module->id,
            'professeur_id' => $prof->id,
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'publiee' => true,
        ]);

        return back()->with('success', 'Annonce publiée avec succès.');
    }

    public function uploaderSupport(Request $request, Module $module)
    {
        $prof = auth()->user()->professeur;
        if ($module->professeur_id !== $prof->id) {
            abort(403);
        }

        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar,png,jpg,jpeg|max:10240', // Max 10MB
            'type' => 'required|in:Cours,TD,TP,Examen,Autre',
        ]);

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $originalName = $file->getClientOriginalName();
            
            // Save to public storage under supports/
            $path = $file->store('supports', 'public');

            Support::create([
                'module_id' => $module->id,
                'professeur_id' => $prof->id,
                'titre' => $request->titre,
                'fichier_path' => $path,
                'fichier_nom' => $originalName,
                'fichier_type' => $file->getMimeType(),
                'type' => $request->type,
                'taille' => $file->getSize(),
            ]);

            return back()->with('success', 'Support de cours mis en ligne.');
        }

        return back()->with('error', 'Fichier non reçu.');
    }

    public function supprimerAnnonce(Annonce $annonce)
    {
        $prof = auth()->user()->professeur;
        if ($annonce->professeur_id !== $prof->id) {
            abort(403);
        }

        $annonce->delete();
        return back()->with('success', 'Annonce supprimée.');
    }

    public function supprimerSupport(Support $support)
    {
        $prof = auth()->user()->professeur;
        if ($support->professeur_id !== $prof->id) {
            abort(403);
        }

        // Delete from disk
        if (Storage::disk('public')->exists($support->fichier_path)) {
            Storage::disk('public')->delete($support->fichier_path);
        }

        $support->delete();
        return back()->with('success', 'Support de cours supprimé.');
    }
}
