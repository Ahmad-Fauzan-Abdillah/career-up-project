<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\UserNotification;

class ApplicationObserver
{
    public function created(Application $application): void
    {
        $application->loadMissing('user', 'jobListing');

        if (! $application->user->is_notification_active) {
            return;
        }

        UserNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Lamaran terkirim',
            'message' => 'Lamaran Anda untuk posisi '.$application->jobListing->title.' telah berhasil dikirim.',
            'url' => route('riwayat.show', $application),
            'type' => 'success',
        ]);
    }

    public function updated(Application $application): void
    {
        if (! $application->wasChanged('status')) {
            return;
        }

        $application->loadMissing('user', 'jobListing');
        if (! $application->user->is_notification_active) {
            return;
        }

        $message = 'Status lamaran Anda untuk '.($application->jobListing?->title ?? 'lowongan')
            ." berubah menjadi {$application->status}.";
        if (filled($application->notes)) {
            $message .= ' Catatan admin: '.$application->notes;
        }

        UserNotification::create([
            'user_id' => $application->user_id,
            'title' => 'Status lamaran diperbarui',
            'message' => $message,
            'url' => route('riwayat.show', $application),
            'type' => in_array($application->status, ['Ditolak', 'Dibatalkan'], true) ? 'warning' : 'info',
        ]);
    }
}
