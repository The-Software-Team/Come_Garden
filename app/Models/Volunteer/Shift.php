<?php

namespace App\Models\Volunteer;
use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relations
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'assignments');
    }

}