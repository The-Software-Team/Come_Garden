<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class SecurityAccessLog extends Model
{
    protected $table = 'security_access_logs';

    protected $fillable = [
        'member_id',
        'gate_code_used',
        'action',
        'gate_location',
        'accessed_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
