<?php

namespace App\Listeners;

use App\Events\SeedWithdrawn;
use Illuminate\Support\Facades\Log;

class LogSeedWithdraw
{
    public function handle(SeedWithdrawn $event): void
    {
        Log::info('Seed withdrawn', [
            'member_id' => $event->memberId,
            'quantity'   => $event->quantity,
        ]);
    }
}