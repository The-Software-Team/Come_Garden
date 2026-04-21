<?php
namespace App\Models;

use App\Models\Rental;
use App\Models\RentalApplication;

class Plot extends BaseModel
{
    protected $fillable = [
        'size',
        'x',
        'y',
        'width',
        'height',
        'area',
        'status',
        'soil_quality',
    ];

    public function rental()
    {
        return $this->hasOne(Rental::class);
    }

    public function waitlist()
    {
        return $this->hasMany(RentalApplication::class);
    }

    public function neighbors()
    {
        return $this->belongsToMany(Plot::class, 'plot_neighbors', 'plot_id', 'neighbor_id');
    }

    public function infections()
    {
        return $this->hasMany(PlotInfection::class);
    }
}