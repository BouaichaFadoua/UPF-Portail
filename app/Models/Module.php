<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model {
    use HasFactory;
    protected $fillable = ['filiere_id','professeur_id','nom','code','coefficient','semestre','volume_horaire'];

    public function filiere()      { return $this->belongsTo(Filiere::class); }
    public function professeur()   { return $this->belongsTo(Professeur::class); }
    public function notes()        { return $this->hasMany(Note::class); }
    public function seances()      { return $this->hasMany(Seance::class); }
    public function annonces()     { return $this->hasMany(Annonce::class)->latest(); }
    public function supports()     { return $this->hasMany(Support::class)->latest(); }
    public function cahierTextes() { return $this->hasMany(CahierTexte::class); }
}
