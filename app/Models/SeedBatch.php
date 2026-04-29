<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeedBatch extends Model
{
    protected $fillable = [
        'member_id',
        'seed_type',
        'quantity',
        'viability',
        'origin',
        'age',
    ];
}