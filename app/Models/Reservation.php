<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model {
    use HasFactory;
    protected $fillable = ['professeur_id','salle_id','date','heure_debut','heure_fin','motif','statut','motif_refus','traite_par'];
    protected $casts = ['date' => 'date'];

    public function professeur() { return $this->belongsTo(Professeur::class); }
    public function salle()      { return $this->belongsTo(Salle::class); }
    public function traitePar()  { return $this->belongsTo(User::class, 'traite_par'); }
}
