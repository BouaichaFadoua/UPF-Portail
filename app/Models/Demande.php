<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Demande extends Model {
    use HasFactory;
    protected $fillable = [
        'user_id','type','statut','motif_refus','traite_par','traite_le',
        'destination','date_depart','date_retour','motif_mission'
    ];
    protected $casts = ['traite_le' => 'datetime', 'date_depart' => 'date', 'date_retour' => 'date'];

    public static array $typeLabels = [
        'attestation_scolarite'  => 'Attestation de scolarité',
        'releve_notes'           => 'Relevé de notes',
        'certificat_inscription' => "Certificat d'inscription",
        'attestation_travail'    => 'Attestation de travail',
        'ordre_mission'          => 'Ordre de mission',
    ];

    public static array $typeRoles = [
        'attestation_scolarite'  => 'etudiant',
        'releve_notes'           => 'etudiant',
        'certificat_inscription' => 'etudiant',
        'attestation_travail'    => 'professeur',
        'ordre_mission'          => 'professeur',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function traitePar() { return $this->belongsTo(User::class, 'traite_par'); }
    public function document()  { return $this->hasOne(Document::class); }

    public function getTypeLabelAttribute(): string {
        return self::$typeLabels[$this->type] ?? $this->type;
    }
}
