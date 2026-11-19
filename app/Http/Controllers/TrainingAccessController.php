<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class TrainingAccessController extends Controller
{
    public function join(Training $training): RedirectResponse
    {
        abort_unless($training->status === 'terbit', 404);

        $user = request()->user();
        if ($training->category === 'Premium' && ! $user->activeSubscription()) {
            abort(403, 'Langganan aktif diperlukan untuk mengikuti pelatihan premium.');
        }

        DB::transaction(function () use ($training, $user): void {
            $lockedTraining = Training::query()->lockForUpdate()->findOrFail($training->id);

            if ($lockedTraining->enrollments()->where('user_id', $user->id)->exists()) {
                return;
            }

            abort_if($lockedTraining->isFull(), 409, 'Kuota pelatihan sudah penuh.');
            $user->trainingEnrollments()->create(['training_id' => $lockedTraining->id]);
        });

        return redirect()->route('pelatihan.show', $training)->with('status', 'Anda berhasil mendaftar pelatihan.');
    }

    public function zoom(Training $training): RedirectResponse
    {
        $enrolled = request()->user()->trainingEnrollments()
            ->where('training_id', $training->id)
            ->exists();
        abort_unless($enrolled, 403, 'Daftar pelatihan terlebih dahulu untuk melihat tautan Zoom.');

        $startAt = $training->startAt();
        $endAt = $training->endAt();
        abort_if(! $startAt || ! $endAt || now()->lt($startAt->copy()->subMinutes(30)) || now()->gt($endAt), 403, 'Tautan Zoom hanya aktif 30 menit sebelum pelatihan hingga selesai.');
        abort_if(blank($training->zoom_link), 404, 'Tautan Zoom pelatihan ini belum tersedia.');

        return redirect()->away($training->zoom_link);
    }
}
