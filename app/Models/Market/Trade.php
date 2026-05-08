<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Trade extends Model
{
protected $fillable = [
    'listing_id',
    'seller_id',
    'buyer_id',
    'quantity',
    'status',
    'note',
    'completed_at',
];
protected $casts = [
    'quantity' => 'decimal:2',
    'completed_at' => 'datetime',
];
public function listing()
{
    return $this->belongsTo(Listing::class);
}

public function seller()
{
    return $this->belongsTo(Member::class, 'seller_id');
}

public function buyer()
{
    return $this->belongsTo(Member::class, 'buyer_id');
}
}
