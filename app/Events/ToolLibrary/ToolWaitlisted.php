<?php

namespace App\Events\ToolLibrary;

use App\Models\ToolLibrary\Tool;
use App\Models\Member;

class ToolWaitlisted
{
    public Tool $tool;
    public Member $member;

    public function __construct(
        Tool $tool,
        Member $member
    ) {
        $this->tool = $tool;
        $this->member = $member;
    }
}