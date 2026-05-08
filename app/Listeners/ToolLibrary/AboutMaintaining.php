<?php

namespace App\Listeners\ToolLibrary;

use App\Events\ToolLibrary\ToolMaintained;
use Illuminate\Support\Facades\Log;

class AboutMaintaining
{
    public function handle(ToolMaintained $event): void
    {
        Log::Info("({$event->tool->name}) Maintained");
    }
}
