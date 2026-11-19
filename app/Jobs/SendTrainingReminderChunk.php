<?php

namespace App\Jobs;

use App\Models\Training;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendTrainingReminderChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  list<int>  $enrollmentIds
     */
    public function __construct(public array $enrollmentIds, public int $trainingId) {}

    public function handle(): void
    {
        $training = Training::query()->findOrFail($this->trainingId);
        $users = DB::table('training_enrollments')
            ->join('users', 'users.id', '=', 'training_enrollments.user_id')
            ->whereIn('training_enrollments.id', $this->enrollmentIds)
            ->where('users.is_notification_active', true)
            ->select('users.id')
            ->get();

        if ($users->isEmpty()) {
            return;
        }

        $now = now();
        $url = route('pelatihan.show', $training);
        DB::table('user_notifications')->insert(
            $users->map(fn (object $user) => [
                'user_id' => $user->id,
                'title' => 'Pengingat pelatihan besok',
                'message' => 'Pelatihan '.$training->title.' dimulai besok. Buka detail untuk melihat jadwal.',
                'url' => $url,
                'type' => 'info',
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all(),
        );
    }
}
