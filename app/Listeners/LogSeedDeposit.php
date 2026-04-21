<?php

namespace App\Listeners;

use App\Events\SeedDeposited;
use Illuminate\Support\Facades\Log;

class LogSeedDeposit
{
    public function handle(SeedDeposited $event): void
    {
        Log::info('Seed deposited', [
            'member_id' => $event->memberId,
            'batch_id'  => $event->batchId,
            'credits'    => $event->credits,
        ]);
    }
}