<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()
            ->userNotifications()
            ->latest()
            ->paginate(10);

        return view('pages.notifikasi', compact('notifications'));
    }

    public function markAllRead(): RedirectResponse
    {
        auth()->user()
            ->userNotifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->route('notifikasi.index')
            ->with('status', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function markRead(UserNotification $notification): RedirectResponse
    {
        Gate::authorize('update', $notification);

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        $url = $notification->url;
        if (is_string($url) && str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return redirect($url);
        }
        if (is_string($url)
            && in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)
            && parse_url($url, PHP_URL_HOST) === request()->getHost()) {
            return redirect($url);
        }

        return redirect()->route('notifikasi.index');
    }
}
