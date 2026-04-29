<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Rental;

use Illuminate\Database\Eloquent\Model;

class RentalParticipant extends Model
{
    protected $fillable = [
        'rental_id',
        'member_id',
        'share',
        'cost',
        'late',
        'auto_renew',
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}