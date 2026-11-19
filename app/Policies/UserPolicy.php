<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewDashboard(User $user): bool
    {
        return $user->isAdmin();
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }
}
