<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Tool;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'tool_id',
        'member_id',
        'start_time',
        'end_time',
        'actual_return_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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