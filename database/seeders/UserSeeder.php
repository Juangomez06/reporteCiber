<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Coordinador (administrador)
        User::create([
            'name' => 'Coordinador',
            'email' => 'coordinador@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_COORDINADOR,
        ]);

        // Estudiante de prueba
        User::create([
            'name' => 'Estudiante',
            'email' => 'estudiante@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ESTUDIANTE,
        ]);
    }
}
