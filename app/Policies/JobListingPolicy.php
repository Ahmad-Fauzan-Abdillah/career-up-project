<?php

namespace App\Policies;

use App\Models\JobListing;
use App\Models\User;

class JobListingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, JobListing $job): bool
    {
        return $user->isAdmin();
    }

    public function publish(User $user, JobListing $job): bool
    {
        return $user->isAdmin();
    }

    public function close(User $user, JobListing $job): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, JobListing $job): bool
    {
        return $user->isAdmin();
    }
}
