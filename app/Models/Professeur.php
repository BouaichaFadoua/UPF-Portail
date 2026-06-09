<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professeur extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','grade','specialite','telephone','bureau'];

    public function user()         { return $this->belongsTo(User::class); }
    public function modules()      { return $this->hasMany(Module::class); }
    public function seances()      { return $this->hasMany(Seance::class); }
    public function cahierTextes() { return $this->hasMany(CahierTexte::class); }
    public function annonces()     { return $this->hasMany(Annonce::class); }
    public function supports()     { return $this->hasMany(Support::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    public function getNomCompletAttribute(): string { return $this->user->name ?? ''; }
}
