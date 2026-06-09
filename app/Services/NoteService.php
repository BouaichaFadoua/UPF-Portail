<?php
namespace App\Services;

use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Module;

class NoteService
{
    public function calculerNoteFinale(float $cc1, float $cc2, float $examen): float
    {
        return round(($cc1 + $cc2) / 2 * 0.4 + $examen * 0.6, 2);
    }

    public function sauvegarderNote(int $etudiantId, int $moduleId, array $data): Note
    {
        $note = Note::firstOrNew([
            'etudiant_id'         => $etudiantId,
            'module_id'           => $moduleId,
            'annee_universitaire' => $data['annee_universitaire'] ?? '2024-2025',
        ]);
        if (isset($data['cc1']))    $note->cc1    = $data['cc1'];
        if (isset($data['cc2']))    $note->cc2    = $data['cc2'];
        if (isset($data['examen'])) $note->examen = $data['examen'];
        $note->save();
        return $note;
    }

    public function getMoyenneGenerale(Etudiant $etudiant, string $annee = '2024-2025'): ?float
    {
        $notes = $etudiant->notes()->where('annee_universitaire', $annee)
            ->whereNotNull('note_finale')->with('module')->get();
        if ($notes->isEmpty()) return null;
        $totalCoeff = $notes->sum(fn($n) => $n->module->coefficient);
        if ($totalCoeff == 0) return null;
        return round($notes->sum(fn($n) => $n->note_finale * $n->module->coefficient) / $totalCoeff, 2);
    }
}
