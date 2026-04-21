<?php

namespace App\Models;

use App\Models\Answer;

class Question extends BaseModel
{
    protected $fillable = [
        'member_id',
        'content',
        'bounty',
        'status',
        'accepted_answer_id',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}