<?php

namespace App\Jobs;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NotifyUsersOfNewJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public function __construct(public int $jobListingId) {}

    public function handle(): void
    {
        $job = JobListing::query()->findOrFail($this->jobListingId);
        $url = $job->type === 'magang'
            ? route('magang.show', $job)
            : route('lowongan.show', $job);

        User::query()
            ->where('role', 'pengguna')
            ->where('is_notification_active', true)
            ->select(['id'])
            ->chunkById(200, function ($users) use ($job, $url): void {
                $now = now();
                DB::table('user_notifications')->insert(
                    $users->map(fn (User $user) => [
                        'user_id' => $user->id,
                        'title' => 'Lowongan baru tersedia',
                        'message' => 'Lowongan '.$job->title.' di '.$job->company_name.' baru saja diterbitkan.',
                        'url' => $url,
                        'type' => 'info',
                        'is_read' => false,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all(),
                );
            });
    }
}
