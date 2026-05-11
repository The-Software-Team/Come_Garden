<?php

namespace App\Models\Volunteer;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;

class FundVote extends Model
{
    protected $table = 'fund_votes';

    protected $fillable = [
        'fund_proposal_id',
        'member_id',
        'vote',
        'comment',
    ];

    public function proposal()
    {
        return $this->belongsTo(FundProposal::class, 'fund_proposal_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
