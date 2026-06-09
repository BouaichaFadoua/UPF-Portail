<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Annonce extends Model {
    use HasFactory;
    protected $fillable = ['module_id','professeur_id','titre','contenu','publiee'];
    protected $casts = ['publiee' => 'boolean'];

    public function module()       { return $this->belongsTo(Module::class); }
    public function professeur()   { return $this->belongsTo(Professeur::class); }
    public function commentaires() { return $this->hasMany(Commentaire::class)->latest(); }
}
