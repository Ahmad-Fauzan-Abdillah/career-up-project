<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Models\UserNotification;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        if (! $subscription->wasChanged('status')) {
            return;
        }

        $subscription->loadMissing('user');
        if (! $subscription->user->is_notification_active) {
            return;
        }

        if ($subscription->status === 'aktif') {
            $title = 'Langganan dikonfirmasi';
            $message = 'Pembayaran langganan Anda telah dikonfirmasi. Paket aktif sampai '
                .$subscription->valid_until?->locale('id')->translatedFormat('d F Y').'.';
            $type = 'success';
        } elseif ($subscription->status === 'ditolak') {
            $title = 'Pembayaran langganan ditolak';
            $message = 'Pembayaran langganan Anda ditolak. Alasan: '.$subscription->rejection_reason;
            $type = 'warning';
        } elseif ($subscription->status === 'kedaluwarsa') {
            $title = 'Langganan berakhir';
            $message = 'Masa aktif langganan premium Anda telah berakhir.';
            $type = 'info';
        } else {
            return;
        }

        UserNotification::create([
            'user_id' => $subscription->user_id,
            'title' => $title,
            'message' => $message,
            'url' => route('subscriptions.index'),
            'type' => $type,
        ]);
    }
}
