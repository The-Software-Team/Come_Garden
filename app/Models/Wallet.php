<?php

namespace App\Models;

use App\Models\Member;
use App\Models\Transaction;


class Wallet extends BaseModel
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'member_id',
        'type',
        'balance',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}