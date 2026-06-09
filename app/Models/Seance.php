<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seance extends Model {
    use HasFactory;
    protected $fillable = ['module_id','groupe_id','salle_id','professeur_id','date','heure_debut','heure_fin','type','semaine'];
    protected $casts = ['date' => 'date'];

    public function module()     { return $this->belongsTo(Module::class); }
    public function groupe()     { return $this->belongsTo(Groupe::class); }
    public function salle()      { return $this->belongsTo(Salle::class); }
    public function professeur() { return $this->belongsTo(Professeur::class); }
    public function absences()   { return $this->hasMany(Absence::class); }
    public function cahierTexte(){ return $this->hasOne(CahierTexte::class); }
}
