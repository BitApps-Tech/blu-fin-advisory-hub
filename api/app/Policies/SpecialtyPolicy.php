<?php

namespace App\Policies;

use App\Models\Specialty;
use App\Models\User;

class SpecialtyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('specialties.view');
    }

    public function view(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.view');
    }

    public function create(User $user): bool
    {
        return $user->can('specialties.create');
    }

    public function update(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.update');
    }

    public function delete(User $user, Specialty $specialty): bool
    {
        return $user->can('specialties.delete');
    }
}

