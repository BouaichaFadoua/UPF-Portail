<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','filiere_id','groupe_id','matricule',
        'date_naissance','lieu_naissance','telephone','adresse','annee_universitaire'
    ];
    protected $casts = ['date_naissance' => 'date'];

    public function user()         { return $this->belongsTo(User::class); }
    public function filiere()      { return $this->belongsTo(Filiere::class); }
    public function groupe()       { return $this->belongsTo(Groupe::class); }
    public function notes()        { return $this->hasMany(Note::class); }
    public function absences()     { return $this->hasMany(Absence::class); }
    public function justificatifs(){ return $this->hasMany(Justificatif::class); }

    public function getNomCompletAttribute(): string { return $this->user->name ?? ''; }

    public function getTotalAbsencesAttribute(): int { return $this->absences()->count(); }

    public function getAbsencesNonJustifieesAttribute(): int {
        return $this->absences()->where('justifiee', false)->count();
    }
}
