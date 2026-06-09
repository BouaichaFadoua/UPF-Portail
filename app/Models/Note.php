<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model {
    use HasFactory;
    protected $fillable = ['etudiant_id','module_id','cc1','cc2','examen','note_finale','annee_universitaire'];

    public function etudiant() { return $this->belongsTo(Etudiant::class); }
    public function module()   { return $this->belongsTo(Module::class); }

    public function calculerNoteFinale(): ?float {
        if ($this->cc1 !== null && $this->cc2 !== null && $this->examen !== null) {
            return round(($this->cc1 + $this->cc2) / 2 * 0.4 + $this->examen * 0.6, 2);
        }
        return null;
    }

    protected static function booted(): void {
        static::saving(function (Note $note) {
            $note->note_finale = $note->calculerNoteFinale();
        });
    }

    public function getMentionAttribute(): string {
        if ($this->note_finale === null) return 'N/A';
        if ($this->note_finale >= 16) return 'Très Bien';
        if ($this->note_finale >= 14) return 'Bien';
        if ($this->note_finale >= 12) return 'Assez Bien';
        if ($this->note_finale >= 10) return 'Passable';
        return 'Insuffisant';
    }

    public function getCouleurAttribute(): string {
        if ($this->note_finale === null) return 'gray';
        if ($this->note_finale >= 16) return 'emerald';
        if ($this->note_finale >= 14) return 'blue';
        if ($this->note_finale >= 12) return 'indigo';
        if ($this->note_finale >= 10) return 'yellow';
        return 'red';
    }
}
