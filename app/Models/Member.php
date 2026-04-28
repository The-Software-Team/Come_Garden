<?php

namespace App\Models;

use App\Models\Penalty;
use App\Models\RentalApplication;
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