<?php

namespace Database\Factories;

use App\Models\Caso;
use App\Models\Institucion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CasoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'institucion_id' => Institucion::factory(),
            'anonimo' => false,
            'tipo_acoso' => fake()->randomElement(Caso::TIPOS),
            'plataforma' => fake()->randomElement(Caso::PLATAFORMAS),
            'descripcion' => fake()->paragraph(4),
            'estado' => 'nuevo',
            'prioridad' => 'media',
        ];
    }

    public function anonimo(): static
    {
        return $this->state(fn () => ['anonimo' => true, 'reporter_id' => null]);
    }
}
