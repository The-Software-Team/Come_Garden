<?php

namespace App\Models\Volunteer;
use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'shift_id',
        'member_id',
        'task_name',
        'status',
        'hours',
    ];

    public function member() {
        return $this->belongsTo(Member::class);
    }

}