<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class MentorshipPair extends Model
{
    protected $table = 'mentorship_pairs';

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'shared_interests',
        'status',
    ];

    protected $casts = [
        'shared_interests' => 'array',
    ];

    public function mentor()
    {
        return $this->belongsTo(Member::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(Member::class, 'mentee_id');
    }
}
