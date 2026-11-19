<?php

namespace App\Observers;

use App\Jobs\NotifyUsersOfNewJob;
use App\Models\JobListing;

class JobListingObserver
{
    public function created(JobListing $job): void
    {
        if ($job->created_by !== null && $job->status === 'terbit') {
            NotifyUsersOfNewJob::dispatch($job->id)->afterCommit();
        }
    }

    public function updated(JobListing $job): void
    {
        if ($job->wasChanged('status') && $job->status === 'terbit') {
            NotifyUsersOfNewJob::dispatch($job->id)->afterCommit();
        }
    }
}
