<?php

namespace App\Models\ToolLibrary;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{

    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'member_id',
        'booking_id',
        'type',
        'severity',
        'amount',
        'status',
        'resolved',
        'reason'
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