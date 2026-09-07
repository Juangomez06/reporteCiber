<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InstitucionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->company().' - Institución Educativa',
            'codigo' => strtoupper(fake()->unique()->bothify('INST-####')),
            'ciudad' => fake()->city(),
            'contacto_email' => fake()->unique()->safeEmail(),
            'contacto_telefono' => fake()->phoneNumber(),
            'activa' => true,
        ];
    }
}
