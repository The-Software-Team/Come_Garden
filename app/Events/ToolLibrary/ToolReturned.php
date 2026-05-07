<?php

namespace App\Events\ToolLibrary;

use App\Models\ToolLibrary\Booking;
use App\Models\ToolLibrary\Tool;

class ToolReturned
{
    public Tool $tool;
    public Booking $booking;

    public function __construct(
        Tool $tool,
        Booking $booking
    ) {
        $this->tool = $tool;
        $this->booking = $booking;
    }
}