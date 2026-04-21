<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\Member;

class Penalty extends BaseModel
{
    protected $fillable = [
        'member_id',
        'booking_id',
        'type',
        'amount',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}