<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Support extends Model {
    use HasFactory;
    protected $fillable = ['module_id','professeur_id','titre','fichier_path','fichier_nom','fichier_type','type','taille'];

    public function module()     { return $this->belongsTo(Module::class); }
    public function professeur() { return $this->belongsTo(Professeur::class); }

    public function getTailleFormateeAttribute(): string {
        if (!$this->taille) return 'N/A';
        $units = ['B','KB','MB','GB'];
        $size  = $this->taille;
        $i = 0;
        while ($size >= 1024 && $i < 3) { $size /= 1024; $i++; }
        return round($size, 2) . ' ' . $units[$i];
    }
}
