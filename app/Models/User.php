<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN      = 'admin';
    const ROLE_PROFESSEUR = 'professeur';
    const ROLE_ETUDIANT   = 'etudiant';
    const ROLE_PERSONNEL  = 'personnel';

    protected $fillable = ['name', 'email', 'password', 'role', 'actif', 'avatar'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'actif'             => 'boolean',
        ];
    }

    public function isAdmin(): bool      { return $this->role === self::ROLE_ADMIN; }
    public function isProfesseur(): bool { return $this->role === self::ROLE_PROFESSEUR; }
    public function isEtudiant(): bool   { return $this->role === self::ROLE_ETUDIANT; }
    public function isPersonnel(): bool  { return $this->role === self::ROLE_PERSONNEL; }

    public function etudiant()           { return $this->hasOne(Etudiant::class); }
    public function professeur()         { return $this->hasOne(Professeur::class); }
    public function personnel()          { return $this->hasOne(Personnel::class); }
    public function commentaires()       { return $this->hasMany(Commentaire::class); }
    public function demandes()           { return $this->hasMany(Demande::class); }
    public function notificationsUpf()   { return $this->hasMany(NotificationUpf::class); }

    public function getAvatarUrlAttribute(): string {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=4f46e5&color=fff&size=128';
    }

    public function getUnreadNotificationsCountAttribute(): int {
        return $this->notificationsUpf()->where('lu', false)->count();
    }
}
