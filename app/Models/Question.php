<?php

namespace App\Models;

use App\Models\Answer;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
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