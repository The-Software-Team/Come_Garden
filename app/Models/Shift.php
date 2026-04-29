<?php

namespace App\Models;

use App\Models\Assignment;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}