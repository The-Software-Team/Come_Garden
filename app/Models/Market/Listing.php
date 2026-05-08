<?php

namespace App\Models\Market;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;

class Listing extends Model
{
protected $fillable = [
    'user_id',
    'produce_name',
    'type',
    'quantity_kg',
    'price',
    'description',
    'pickup_location',
    'pickup_window_hours',
    'expires_at',
    'status',
    'allergen_flags',
    'quality_score',
];

protected $casts = [
    'expires_at' => 'datetime',
    'quantity_kg' => 'decimal:2',
    'price' => 'decimal:2',
    'quality_score' => 'decimal:1',
];
    public function user()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function trades()
    {
        return $this->hasMany(Trade::class);
    }

    public function qualityRatings()
    {
        return $this->hasMany(QualityRating::class);
    }
}
