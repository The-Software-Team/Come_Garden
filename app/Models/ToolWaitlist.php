<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Tool;

use Illuminate\Database\Eloquent\Model;

class ToolWaitlist extends Model
{
    protected $fillable = [
        'tool_id',
        'member_id',
        'priority',
        'duration',
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