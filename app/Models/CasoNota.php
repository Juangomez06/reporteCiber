<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasoNota extends Model
{
    protected $fillable = ['caso_id', 'autor_id', 'contenido', 'privada'];

    protected $casts = ['privada' => 'boolean'];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autor_id');
    }
}
