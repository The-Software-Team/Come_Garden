<?php

namespace App\Models;

use App\Models\Member;

class Listing extends BaseModel
{
    protected $fillable = [
        'member_id',
        'item',
        'quantity',
        'type',
        'status',
        'request',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}