<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Application $application): bool
    {
        return $user->isAdmin() || $application->user_id === $user->id;
    }

    public function updateStatus(User $user, Application $application): bool
    {
        return $user->isAdmin();
    }

    public function cancel(User $user, Application $application): bool
    {
        return $application->user_id === $user->id && $application->status === 'Diproses';
    }
}
