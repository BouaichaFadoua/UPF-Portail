<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model {
    use HasFactory;
    protected $fillable = ['demande_id','fichier_path','fichier_nom','generated_at'];
    protected $casts    = ['generated_at' => 'datetime'];

    public function demande() { return $this->belongsTo(Demande::class); }
}
