<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class QualityRating extends Model
{
    //
protected $fillable = [
    'listing_id',
    'user_id',
    'score',
    'review',
];
public function listing()
{
    return $this->belongsTo(Listing::class);
}

public function user()
{
    return $this->belongsTo(Member::class, 'user_id');
}
}
