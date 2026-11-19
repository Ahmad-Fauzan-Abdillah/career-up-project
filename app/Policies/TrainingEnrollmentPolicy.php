<?php

namespace App\Policies;

use App\Models\TrainingEnrollment;
use App\Models\User;

class TrainingEnrollmentPolicy
{
    public function view(User $user, TrainingEnrollment $enrollment): bool
    {
        return $user->isAdmin() || $enrollment->user_id === $user->id;
    }
}
