<?php

namespace App\Policies;

use App\Models\Caso;
use App\Models\User;

class CasoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCoordinador() || $user->isEstudiante();
    }

    public function view(User $user, Caso $caso): bool
    {
        if ($user->isCoordinador()) {
            return true;                 // 👈 el coordinador ve cualquier caso, de cualquier IE
        }

        // Estudiante: solo ve casos que reportó o que orienta
        return $caso->reporter_id === $user->id
            || $caso->orientador_id === $user->id;
    }

    public function update(User $user, Caso $caso): bool
    {
        if ($user->isCoordinador()) {
            return true;                 // 👈 mismo criterio
        }

        return $caso->orientador_id === $user->id;
    }

    public function assign(User $user, Caso $caso): bool
    {
        return $user->isCoordinador();
    }

    public function delete(User $user, Caso $caso): bool
    {
        return $user->isCoordinador();
    }
}
