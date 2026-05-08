<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class FundProposal extends Model
{
    protected $table = 'fund_proposals';

    protected $fillable = [
        'title',
        'description',
        'estimated_cost',
        'proposed_by',
        'status',
        'voting_ends_at',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'voting_ends_at' => 'datetime',
    ];

    public function proposer()
    {
        return $this->belongsTo(Member::class, 'proposed_by');
    }

    public function votes()
    {
        return $this->hasMany(FundVote::class, 'fund_proposal_id');
    }
}
