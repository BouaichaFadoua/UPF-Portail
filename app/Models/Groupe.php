<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupe extends Model {
    use HasFactory;
    protected $fillable = ['filiere_id','nom','capacite'];
    public function filiere()   { return $this->belongsTo(Filiere::class); }
    public function etudiants() { return $this->hasMany(Etudiant::class); }
    public function seances()   { return $this->hasMany(Seance::class); }
}
