<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'poste', 'service', 'telephone'];

    public function user() { return $this->belongsTo(User::class); }

    public function getNomCompletAttribute(): string { return $this->user->name ?? ''; }
}
