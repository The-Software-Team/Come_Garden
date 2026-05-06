<?php

namespace App\Models;

use App\Models\Penalty;
use App\Models\RentalParticipant;
use App\Models\Wallet;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    // Roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'member_role');
    }


    // Wallets
    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function getWallet(string $type): Wallet
    {
        return $this->wallets()->firstOrCreate(
            ['type' => $type],
            ['balance' => 0]
        );
    }

    // rentals
    public function rentals()
    {
        return $this->hasManyThrough(
            Rental::class,
            RentalParticipant::class,
            'member_id',   // FK on participants
            'id',          // FK on rentals
            'id',          // local key
            'rental_id'    // local key on participants
        );
    }
    
    public function rentalParticipations()
    {
        return $this->hasMany(RentalParticipant::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    // helpers

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}