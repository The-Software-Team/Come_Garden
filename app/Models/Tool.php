<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\ToolWaitlist;

class Tool extends BaseModel
{
    protected $fillable = [
        'name',
        'status',
        'usage_status',
        'maintenance_threshold_hours',
        'total_usage_hours',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function waitlist()
    {
        return $this->hasMany(ToolWaitlist::class);
    }
}