<?php

namespace App\Listeners\ToolLibrary;

use App\Events\ToolLibrary\ToolBooked;
use Illuminate\Support\Facades\Log;

class AboutBooking
{
    public function handle(ToolBooked $event): void
    {
        Log::Info("({$event->tool->name}) booked by ({$event->member->name})");
    }
}

