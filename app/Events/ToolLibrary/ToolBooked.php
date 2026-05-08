<?php

namespace App\Events\ToolLibrary;

use App\Models\ToolLibrary\Tool;
use App\Models\ToolLibrary\Booking;
use App\Models\Member;

class ToolBooked
{
    public Tool $tool;
    public Booking $booking;
    public Member $member;

    public function __construct(
        Tool $tool,
        Booking $booking,
        Member $member
    ) {
        $this->tool = $tool;
        $this->booking = $booking;
        $this->member = $member;
    }
}