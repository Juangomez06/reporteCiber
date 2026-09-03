<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

// Dentro de la clase User

    //Asignación de roles
    public const ROLE_COORDINADOR = 'coordinador';
    public const ROLE_ESTUDIANTE = 'estudiante';

    public function isCoordinador(): bool
    {
        return $this->role === self::ROLE_COORDINADOR;
    }

    public function isEstudiante(): bool
    {
        return $this->role === self::ROLE_ESTUDIANTE;
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'doc',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
