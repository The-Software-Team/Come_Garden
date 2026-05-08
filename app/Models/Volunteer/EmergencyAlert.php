<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class EmergencyAlert extends Model
{
    protected $table = 'emergency_alerts';

    protected $fillable = [
        'created_by',
        'title',
        'message',
        'severity',
        'is_active',
        'resolved_at',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'resolved_at'  => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(Member::class, 'created_by');
    }
}
