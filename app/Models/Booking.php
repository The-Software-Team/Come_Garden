<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Tool;

class Booking extends BaseModel
{
    protected $fillable = [
        'tool_id',
        'member_id',
        'start_time',
        'end_time',
        'actual_return_time',
        'status',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}