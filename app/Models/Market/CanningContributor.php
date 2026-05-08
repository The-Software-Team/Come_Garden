<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class CanningContributor extends Model
{
    //
protected $fillable = [
    'session_id',
    'user_id',
    'produce_name',
    'quantity_kg',
];
public function session()
{
    return $this->belongsTo(CanningSession::class, 'session_id');
}

public function user()
{
    return $this->belongsTo(Member::class, 'user_id');
}
}
