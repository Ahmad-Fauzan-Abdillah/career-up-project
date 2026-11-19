<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';

    protected $description = 'Expire active subscriptions whose validity period has ended';

    public function handle(): int
    {
        Subscription::query()
            ->where('status', 'aktif')
            ->whereDate('valid_until', '<', today())
            ->chunkById(100, function ($subscriptions): void {
                foreach ($subscriptions as $subscription) {
                    $subscription->update(['status' => 'kedaluwarsa']);
                }
            });

        $this->info('Expired subscriptions updated.');

        return self::SUCCESS;
    }
}
