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
            return $user->institucion_id === $caso->institucion_id || $user->institucion_id === null;
        }

        // Estudiante: solo puede ver casos que reportó (si no es anónimo) o casos que orienta
        return $caso->reporter_id === $user->id || $caso->orientador_id === $user->id;
    }

    public function update(User $user, Caso $caso): bool
    {
        if ($user->isCoordinador()) {
            return $user->institucion_id === $caso->institucion_id || $user->institucion_id === null;
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
