<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CasoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'institucion' => $this->whenLoaded('institucion', fn () => [
                'id' => $this->institucion->id,
                'nombre' => $this->institucion->nombre,
            ]),
            'anonimo' => $this->anonimo,
            'reportante' => $this->anonimo ? null : $this->whenLoaded('reporter', fn () => $this->reporter?->name),
            'orientador' => $this->whenLoaded('orientador', fn () => $this->orientador?->only(['id', 'name'])),
            'tipo_acoso' => $this->tipo_acoso,
            'plataforma' => $this->plataforma,
            'descripcion' => $this->descripcion,
            'estado' => $this->estado,
            'prioridad' => $this->prioridad,
            'resuelto_at' => $this->resuelto_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
