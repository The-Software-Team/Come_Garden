<?php

namespace App\Models;

use App\Models\Wallet;

class Transaction extends BaseModel
{
    protected $fillable = [
        'wallet_id',
        'amount',
        'type',
        'reason',
        'reference_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}