<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNotificationPreferenceRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('pages.pengaturan', [
            'user' => auth()->user(),
        ]);
    }

    public function editPassword(): View
    {
        return view('pages.pengaturan-password');
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('new_password')),
        ]);

        return redirect()->route('pengaturan.index')
            ->with('status', 'Kata sandi berhasil diperbarui.');
    }

    public function emailStatus(): View
    {
        return view('pages.pengaturan-email', [
            'user' => auth()->user(),
        ]);
    }

    public function updateNotificationPreference(UpdateNotificationPreferenceRequest $request): JsonResponse
    {
        $request->user()->update([
            'is_notification_active' => $request->validated('is_notification_active'),
        ]);

        return response()->json([
            'is_notification_active' => $request->user()->fresh()->is_notification_active,
        ]);
    }
}
