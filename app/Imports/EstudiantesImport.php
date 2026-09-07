<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Estudiante;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class EstudiantesImport implements ToCollection, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation
{
    // Propiedades estáticas para compartir entre chunks
    private static $emailsExistentes = null;
    private static $emailsUsados = [];

    public function collection(Collection $rows)
    {
        // Cargar emails existentes una sola vez (la primera vez que se llama)
        if (self::$emailsExistentes === null) {
            self::$emailsExistentes = User::whereNotNull('email')
                ->pluck('email')
                ->map(fn($e) => strtolower(trim($e)))
                ->all();
        }

        foreach ($rows as $row) {
            // Saltar filas sin documento
            if (empty($row['doc'])) continue;

            $doc = trim((string) $row['doc']); // Asegurar string sin espacios

            // Determinar email (normalizado)
            $email = !empty($row['correo']) ? strtolower(trim($row['correo'])) : null;

            // Verificar si el email ya existe o ya fue usado en esta importación
            if ($email && (in_array($email, self::$emailsExistentes) || in_array($email, self::$emailsUsados))) {
                $email = null; // No usar email duplicado
            } elseif ($email) {
                self::$emailsUsados[] = $email; // Registrar como usado
            }

            // Crear o actualizar usuario (buscar por documento)
            $user = User::updateOrCreate(
                ['doc' => $doc],
                [
                    'name' => trim(($row['nombre1'] ?? '') . ' ' . ($row['apellido1'] ?? '')),
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => User::ROLE_ESTUDIANTE,
                ]
            );

            // Crear o actualizar estudiante
            Estudiante::updateOrCreate(
                ['doc' => $doc],
                [
                    'user_id' => $user->id,
                    'sede' => $row['sede'] ?? null,
                    'jornada' => $row['jornada'] ?? null,
                    'fechaini' => $this->parseDate($row['fechaini'] ?? null),
                    'estrato' => $row['estrato'] ?? null,
                    'sisben' => $row['sisben_iv'] ?? null,
                    'tipodoc' => $row['tipodoc'] ?? null,
                    'apellido1' => $row['apellido1'] ?? null,
                    'apellido2' => $row['apellido2'] ?? null,
                    'nombre1' => $row['nombre1'] ?? null,
                    'nombre2' => $row['nombre2'] ?? null,
                    'genero' => $row['genero'] ?? null,
                    'fecha_nacimiento' => $this->parseDate($row['fecha_nacimiento'] ?? null),
                    'barrio' => $row['barrio'] ?? null,
                    'eps' => $row['eps'] ?? null,
                    'tipo_sangre' => $row['tipo_de_sangre'] ?? null,
                    'discapacidad' => $row['discapacidad'] ?? null,
                    'pais_origen' => $row['pais_origen'] ?? null,
                    'telefono' => $row['telefono'] ?? null,
                ]
            );
        }
    }

    private function parseDate($value)
    {
        if (empty($value)) return null;
        try {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return ['doc' => 'required'];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
