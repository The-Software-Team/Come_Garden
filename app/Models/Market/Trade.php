<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Trade extends Model
{
    protected $fillable = [
        'listing_id',
        'member_id',
        'status',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}