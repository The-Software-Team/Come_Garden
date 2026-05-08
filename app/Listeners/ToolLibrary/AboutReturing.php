<?php

namespace App\Listeners\ToolLibrary;

use App\Events\ToolLibrary\ToolReturned;
use Illuminate\Support\Facades\Log;

class AboutReturing
{
    public function handle(ToolReturned $event): void
    {
        Log::Info("({$event->tool->name}) returned from booking id: ({$event->booking->id})");
    }
}
