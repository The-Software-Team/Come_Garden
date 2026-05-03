<?php

namespace App\Models;

use App\Models\Plot\Plot;
use App\Models\RentalParticipant;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
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