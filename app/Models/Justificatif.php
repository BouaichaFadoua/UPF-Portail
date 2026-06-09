<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Justificatif extends Model {
    use HasFactory;
    protected $fillable = ['absence_id','etudiant_id','fichier_path','fichier_nom','statut','motif_rejet','traite_par','traite_le'];
    protected $casts = ['traite_le' => 'datetime'];

    public function absence()   { return $this->belongsTo(Absence::class); }
    public function etudiant()  { return $this->belongsTo(Etudiant::class); }
    public function traitePar() { return $this->belongsTo(User::class, 'traite_par'); }
}
