<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\ToolWaitlist;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = [
        'name',
        'usage_status',
        'maintenance_threshold_hours',
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