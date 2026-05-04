<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'member_id',
        'content',
        'accepted',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}