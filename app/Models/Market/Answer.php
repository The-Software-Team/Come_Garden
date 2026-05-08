<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Answer extends Model
{
    //
protected $fillable = [
    'question_id',
    'user_id',
    'body',
    'is_accepted',
];
public function question()
{
    return $this->belongsTo(Question::class);
}

public function user()
{
    return $this->belongsTo(Member::class, 'user_id');
}
}
