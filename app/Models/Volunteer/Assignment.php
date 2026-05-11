<?php

namespace App\Models\Volunteer;
use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'shift_id',
        'member_id',
        'shift_task_id',
        'task_name',
        'role',
        'status',
        'hours',
    ];

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function shift() {
        return $this->belongsTo(Shift::class);
    }

    public function task() {
        return $this->belongsTo(Task::class, 'shift_task_id');
    }

}