<?php

namespace App\Models;

use App\Models\Plot;
use App\Models\RentalParticipant;

class Rental extends BaseModel
{
    protected $fillable = [
        'plot_id',
        'season_id',
        'status',
        'start_date',
        'end_date',
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function participants()
    {
        return $this->hasMany(RentalParticipant::class);
    }
}