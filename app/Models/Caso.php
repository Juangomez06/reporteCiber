<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Caso extends Model
{
    use HasFactory, SoftDeletes;

    public const ESTADOS = ['nuevo', 'en_revision', 'asignado', 'en_proceso', 'resuelto', 'cerrado'];
    public const TIPOS = ['ciberacoso', 'suplantacion', 'sextorsion', 'grooming', 'discurso_odio', 'exclusion_social', 'otro'];
    public const PLATAFORMAS = ['whatsapp', 'instagram', 'tiktok', 'facebook', 'x', 'juego_online', 'correo', 'presencial_digital', 'otro'];

    protected $fillable = [
        'codigo', 'institucion_id', 'reporter_id', 'anonimo', 'orientador_id',
        'tipo_acoso', 'plataforma', 'descripcion', 'estado', 'prioridad', 'resuelto_at',
    ];

    protected $casts = [
        'anonimo' => 'boolean',
        'resuelto_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Caso $caso) {
            $caso->codigo ??= 'CASO-'.strtoupper(Str::random(8));
            if ($caso->anonimo) {
                $caso->reporter_id = null;
            }
        });
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function orientador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orientador_id');
    }

    public function evidencias(): HasMany
    {
        return $this->hasMany(CasoEvidencia::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(CasoNota::class);
    }

    public function historial(): HasMany
    {
        return $this->hasMany(CasoHistorial::class)->latest();
    }

    public function registrarHistorial(string $accion, ?string $anterior = null, ?string $nuevo = null): void
    {
        $this->historial()->create([
            'user_id' => auth()->id(),
            'accion' => $accion,
            'valor_anterior' => $anterior,
            'valor_nuevo' => $nuevo,
        ]);
    }
}
