<?php

namespace App\Models;

class SwapRequest extends BaseModel
{
    protected $fillable = [
        'requester_id',
        'target_id',
        'shift_id',
        'status',
    ];
}