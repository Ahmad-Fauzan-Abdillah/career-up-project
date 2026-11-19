<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Auth\Events\Registered;

class SendWelcomeNotification
{
    public function handle(Registered $event): void
    {
        /** @var User $user */
        $user = $event->user;
        if (! $user->is_notification_active) {
            return;
        }

        UserNotification::create([
            'user_id' => $user->id,
            'title' => 'Selamat datang di CareerUp',
            'message' => 'Akun Anda berhasil dibuat. Lengkapi profil untuk memulai perjalanan karier.',
            'url' => route('beranda'),
            'type' => 'success',
        ]);
    }
}
