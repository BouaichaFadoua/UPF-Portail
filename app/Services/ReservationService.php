<?php
namespace App\Services;

use App\Models\Reservation;
use App\Models\Salle;

class ReservationService
{
    public function verifierConflit(int $salleId, string $date, string $debut, string $fin, ?int $excludeId = null): bool
    {
        $query = Reservation::where('salle_id', $salleId)
            ->where('date', $date)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->where('heure_debut', '<', $fin)
            ->where('heure_fin', '>', $debut);
        if ($excludeId) $query->where('id', '!=', $excludeId);
        return $query->exists();
    }

    public function getSallesDisponibles(string $date, string $debut, string $fin): \Illuminate\Database\Eloquent\Collection
    {
        $occupees = Reservation::where('date', $date)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->where('heure_debut', '<', $fin)->where('heure_fin', '>', $debut)
            ->pluck('salle_id');
        return Salle::whereNotIn('id', $occupees)->where('disponible', true)->get();
    }
}
