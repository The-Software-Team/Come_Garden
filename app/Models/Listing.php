<?php

namespace App\Models;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $fillable = [
        'member_id',
        'item',
        'quantity',
        'type',
        'request',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}