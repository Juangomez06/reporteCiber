<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

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

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function casosOrientados(): HasMany
    {
        return $this->hasMany(Caso::class, 'orientador_id');
    }

    public function casosReportados(): HasMany
    {
        return $this->hasMany(Caso::class, 'reporter_id');
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
        'institucion_id',
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
