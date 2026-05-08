<?php

namespace App\Models\ToolLibrary;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class ToolWaitlist extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    protected $fillable = [
        'tool_id',
        'member_id',
        'priority_score',
        'duration_hours',
        'status'
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