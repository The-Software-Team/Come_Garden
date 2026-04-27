<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Season extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
    ];

    /*
    |-----------------------------
    | Relationships
    |-----------------------------
    */

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /*
    |-----------------------------
    | Helpers
    |-----------------------------
    */

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming';
    }
}