<?php

namespace App\Policies;

use App\Models\Institucion;
use App\Models\User;

class InstitucionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isCoordinador();
    }

    public function view(User $user, Institucion $institucion): bool
    {
        return $user->isCoordinador();
    }

    public function create(User $user): bool
    {
        return $user->isCoordinador();
    }

    public function update(User $user, Institucion $institucion): bool
    {
        return $user->isCoordinador();
    }

    public function delete(User $user, Institucion $institucion): bool
    {
        return $user->isCoordinador();
    }
}
