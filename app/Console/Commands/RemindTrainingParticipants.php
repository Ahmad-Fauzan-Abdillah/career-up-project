<?php

namespace App\Console\Commands;

use App\Jobs\SendTrainingReminderChunk;
use App\Models\Training;
use Illuminate\Console\Command;

class RemindTrainingParticipants extends Command
{
    protected $signature = 'trainings:remind';

    protected $description = 'Queue H-1 reminders for enrolled training participants';

    public function handle(): int
    {
        Training::query()
            ->published()
            ->whereDate('schedule_date', today()->addDay())
            ->chunkById(100, function ($trainings): void {
                foreach ($trainings as $training) {
                    $training->enrollments()
                        ->whereNull('reminder_sent_at')
                        ->select(['id'])
                        ->chunkById(200, function ($enrollments) use ($training): void {
                            $ids = $enrollments->modelKeys();
                            if ($ids === []) {
                                return;
                            }

                            $training->enrollments()->whereKey($ids)->update(['reminder_sent_at' => now()]);
                            SendTrainingReminderChunk::dispatch($ids, $training->id);
                        });
                }
            });

        $this->info('Training reminders queued.');

        return self::SUCCESS;
    }
}
