<?php

namespace Database\Seeders;

use App\Models\Institucion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $institucion = Institucion::first();

        // Coordinador (administrador)
        User::create([
            'name' => 'Coordinador',
            'email' => 'coordinador@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_COORDINADOR,
            'institucion_id' => $institucion?->id,
        ]);

        // Estudiante de prueba
        User::create([
            'name' => 'Estudiante',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ESTUDIANTE,
            'institucion_id' => $institucion?->id,
        ]);
    }
}
