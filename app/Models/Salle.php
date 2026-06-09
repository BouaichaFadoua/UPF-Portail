<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salle extends Model {
    use HasFactory;
    protected $fillable = ['nom','capacite','type','batiment','disponible'];
    protected $casts = ['disponible' => 'boolean'];

    public function seances()      { return $this->hasMany(Seance::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    public function isDisponibleCreneaux(string $date, string $debut, string $fin): bool {
        $seanceOccupee = $this->seances()->where('date', $date)
            ->where('heure_debut', '<', $fin)->where('heure_fin', '>', $debut)->exists();
        $reservationOccupee = $this->reservations()->where('date', $date)
            ->whereIn('statut', ['en_attente','approuvee'])
            ->where('heure_debut', '<', $fin)->where('heure_fin', '>', $debut)->exists();
        return !$seanceOccupee && !$reservationOccupee;
    }
}
