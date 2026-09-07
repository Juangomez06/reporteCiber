<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CasoHistorial extends Model
{
    protected $fillable = ['caso_id', 'user_id', 'accion', 'valor_anterior', 'valor_nuevo'];

    public function caso(): BelongsTo
    {
        return $this->belongsTo(Caso::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
