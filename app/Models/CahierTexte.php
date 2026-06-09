<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CahierTexte extends Model {
    use HasFactory;
    protected $table = 'cahier_textes';
    protected $fillable = ['professeur_id','module_id','seance_id','date','heure_debut','heure_fin','objectif','nature','contenu'];
    protected $casts = ['date' => 'date'];

    public function professeur() { return $this->belongsTo(Professeur::class); }
    public function module()     { return $this->belongsTo(Module::class); }
    public function seance()     { return $this->belongsTo(Seance::class); }
}
