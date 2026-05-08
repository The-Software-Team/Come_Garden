<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $table = 'incidents';

    protected $fillable = [
        'reported_by',
        'title',
        'description',
        'location',
        'severity',
        'status',
        'assigned_to',
        'resolution_notes',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(Member::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(Member::class, 'assigned_to');
    }
}
