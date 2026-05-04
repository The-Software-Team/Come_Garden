<?php

namespace App\Models\Volunteer;
use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class SwapRequest extends Model
{
    protected $fillable = [
        'shift_id',
        'requester_id',
        'target_id',
        'assignment_id',
        'status',
        'reason',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function requester()
    {
        return $this->belongsTo(Member::class, 'requester_id');
    }

    public function target()
    {
        return $this->belongsTo(Member::class, 'target_id');
    }

}