<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedBatch extends Model
{
    protected $fillable = [
        'owner_id',
        'owner_type',
        'seed_type',
        'quantity',
        'viability',
        'origin',
        'age'
    ];
}