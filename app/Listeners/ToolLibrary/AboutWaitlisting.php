<?php

namespace App\Listeners\ToolLibrary;

use App\Events\ToolLibrary\ToolWaitlisted;
use Illuminate\Support\Facades\Log;

class AboutWaitlisting
{
    public function handle(ToolWaitlisted $event): void
    {
        Log::Info("({$event->tool->name}) waitlisted for member ({$event->member->name})");
    }
}

