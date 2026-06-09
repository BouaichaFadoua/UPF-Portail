<?php
namespace App\Services;

use App\Models\Demande;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Professeur;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PdfService
{
    public function genererDocument(Demande $demande): string
    {
        $user = $demande->user;
        // Map types to view names:
        // attestation_scolarite -> pdf.attestation-scolarite
        // releve_notes          -> pdf.releve-notes
        // certificat_inscription-> pdf.certificat-inscription
        // attestation_travail   -> pdf.attestation-travail
        // ordre_mission         -> pdf.ordre-mission
        $viewName = 'pdf.' . str_replace('_', '-', $demande->type);
        
        $data = [
            'demande' => $demande,
            'user' => $user,
            'date' => Carbon::now()->format('d/m/Y'),
        ];

        if ($user->isEtudiant() && $user->etudiant) {
            $data['etudiant'] = $user->etudiant;
            if ($demande->type === 'releve_notes') {
                $noteService = new NoteService();
                $data['notes'] = $user->etudiant->notes()
                    ->where('annee_universitaire', $user->etudiant->annee_universitaire)
                    ->with('module')->get();
                $data['moyenne'] = $noteService->getMoyenneGenerale($user->etudiant);
            }
        } elseif ($user->isProfesseur() && $user->professeur) {
            $data['professeur'] = $user->professeur;
        }

        $pdf = Pdf::loadView($viewName, $data);
        
        $fileName = $demande->type . '_' . $user->id . '_' . time() . '.pdf';
        $filePath = 'documents/' . $fileName;
        
        // Ensure storage directory exists
        Storage::disk('public')->makeDirectory('documents');
        
        // Save pdf to public storage
        Storage::disk('public')->put($filePath, $pdf->output());
        
        return $filePath;
    }
}
