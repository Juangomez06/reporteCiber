<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasoEvidencia extends Model
{
    protected $fillable = ['caso_id', 'ruta', 'nombre_original', 'mime', 'tamano'];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }
}
