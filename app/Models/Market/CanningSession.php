<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class CanningSession extends Model
{
    //
protected $fillable = [
    'organizer_id',
    'title',
    'description',
    'location',
    'scheduled_at',
    'max_members',
    'current_count',
    'status',
];
protected $casts = [
    'scheduled_at' => 'datetime',
];

public function organizer()
{
    return $this->belongsTo(Member::class, 'organizer_id');
}

public function contributors()
{
    return $this->hasMany(CanningContributor::class, 'session_id');
}
}
