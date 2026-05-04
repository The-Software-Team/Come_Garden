<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'shift_id',
        'name',
        'category', // heavy | light
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}