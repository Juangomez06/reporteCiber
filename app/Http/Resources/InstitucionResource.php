<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstitucionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'ciudad' => $this->ciudad,
            'activa' => $this->activa,
            'casos_count' => $this->whenCounted('casos'),
        ];
    }
}
