<?php

namespace App\Models;

class SeedBatch extends BaseModel
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