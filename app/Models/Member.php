<?php

namespace App\Models;

use App\Models\Penalty;
use App\Models\RentalApplication;
use App\Models\RentalParticipant;
use App\Models\Wallet;

class Member extends BaseModel
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Wallets
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    // Rentals
    public function rentals()
    {
        return $this->hasMany(RentalParticipant::class);
    }

    // Applications
    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }
}