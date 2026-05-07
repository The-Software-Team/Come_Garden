<?php

namespace App\Models\ToolLibrary;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'tool_id',
        'member_id',
        'start_time',
        'end_time',
        'actual_return_time',
        'status',
        'qr_token',
        'picked_up_at',
        'returned_scanned_at',
        'cleaned_at'
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