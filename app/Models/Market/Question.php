<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Question extends Model
{
    //
protected $fillable = [
    'user_id',
    'title',
    'body',
    'is_resolved',
];
public function user()
{
    return $this->belongsTo(Member::class, 'user_id');
}

public function answers()
{
    return $this->hasMany(Answer::class);
}
}
