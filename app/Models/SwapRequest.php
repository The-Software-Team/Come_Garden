<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwapRequest extends Model
{
    protected $fillable = [
        'requester_id',
        'target_id',
        'shift_id',
        'status',
    ];
}