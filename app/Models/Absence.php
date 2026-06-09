<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model {
    use HasFactory;
    protected $fillable = ['etudiant_id','seance_id','justifiee','motif'];
    protected $casts = ['justifiee' => 'boolean'];

    public function etudiant()     { return $this->belongsTo(Etudiant::class); }
    public function seance()       { return $this->belongsTo(Seance::class); }
    public function justificatif() { return $this->hasOne(Justificatif::class); }
}
