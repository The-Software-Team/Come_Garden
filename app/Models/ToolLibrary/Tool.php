<?php

namespace App\Models\ToolLibrary;


use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'status',
        'usage_status',
        'total_usage_hours',
        'maintenance_threshold_hours',
        'is_active'
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