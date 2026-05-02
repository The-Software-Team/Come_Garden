<?php
namespace App\Models;

use App\Models\Rental;
use App\Models\RentalApplication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',
        'x',
        'y',
        'width',
        'height',
        'area',
        'status',
        'soil_quality',
        'infection_status',
        'infection_type',
        'infection_date',
    ];


    public function rental()
    {
        return $this->hasOne(Rental::class);
    }

    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class);
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

    public function crops()
    {
        return $this->hasMany(PlotCrop::class);
    }

    public function getAlertsAttribute()
    {
        return $this->infections->map(function ($infection) {
            return [
                'message' => "Infection: {$infection->type}",
                'severity' => $infection->severity,
            ];
        });
    }
}