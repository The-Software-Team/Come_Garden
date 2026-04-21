<?php

namespace App\Models;

class Answer extends BaseModel
{
    protected $fillable = [
        'question_id',
        'member_id',
        'content',
        'accepted',
    ];
}