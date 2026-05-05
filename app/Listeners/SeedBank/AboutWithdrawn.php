<?php

namespace App\Listeners\SeedBank;

use App\Events\SeedBank\SeedWithdrawn;
use Illuminate\Support\Facades\Log;
use App\Models\SeedBatch;

class AboutWithdrawn
{
    public function handle(SeedWithdrawn $event): void
    {
        Log::info("Removed All SeedBatches that has quantity of 0");

        SeedBatch::where('owner_type', 'market')
            ->where('quantity', 0)
            ->delete();
    }
}
