<?php

namespace App\Models;

class Assignment extends BaseModel
{
    protected $fillable = [
        'shift_id',
        'member_id',
        'task_name',
        'status',
        'hours',
    ];
}