<?php

namespace App\Models\Market;

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

    public function acceptedAnswer()
    {
        return $this->belongsTo(Answer::class, 'accepted_answer_id');
    }

}