<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
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