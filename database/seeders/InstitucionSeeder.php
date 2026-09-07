<?php

namespace Database\Seeders;

use App\Models\Institucion;
use Illuminate\Database\Seeder;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        Institucion::create([
            'nombre' => 'Institución Educativa Demo',
            'codigo' => 'DEMO-001',
            'ciudad' => 'Garzón',
            'contacto_email' => 'contacto@institucion-demo.edu',
            'activa' => true,
        ]);
    }
}
