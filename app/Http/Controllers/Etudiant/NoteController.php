<?php
namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Services\NoteService;

class NoteController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        $notes = Note::with('module')
            ->where('etudiant_id', $etudiant->id)
            ->where('annee_universitaire', $etudiant->annee_universitaire)
            ->get();

        $noteService = new NoteService();
        $moyenne = $noteService->getMoyenneGenerale($etudiant);

        return view('etudiant.notes.index', compact('notes', 'moyenne'));
    }
}
