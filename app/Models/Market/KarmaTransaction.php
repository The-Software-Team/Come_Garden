<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class KarmaTransaction extends Model
{
    //
protected $fillable = [
    'user_id',
    'points',
    'reason',
    'reference_id',
    'description',
];
public function user()
{
    return $this->belongsTo(Member::class, 'user_id');
}
}
