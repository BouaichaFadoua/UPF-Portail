<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model {
    use HasFactory;
    protected $fillable = ['nom','code','niveau','departement'];
    public function modules()  { return $this->hasMany(Module::class); }
    public function groupes()  { return $this->hasMany(Groupe::class); }
    public function etudiants(){ return $this->hasMany(Etudiant::class); }
}
