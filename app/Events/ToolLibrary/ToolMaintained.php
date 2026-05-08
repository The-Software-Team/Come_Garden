<?php

namespace App\Events\ToolLibrary;

use App\Models\ToolLibrary\Tool;

class ToolMaintained
{
    public Tool $tool;

    public function __construct(
        Tool $tool,
    ) {
        $this->tool = $tool;
    }
}