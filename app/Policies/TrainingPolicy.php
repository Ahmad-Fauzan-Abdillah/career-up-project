<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;

class TrainingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Training $training): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Training $training): bool
    {
        return $user->isAdmin();
    }

    public function viewParticipants(User $user, Training $training): bool
    {
        return $user->isAdmin();
    }
}
