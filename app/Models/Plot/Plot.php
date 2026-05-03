<?php
namespace App\Models\Plot;

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


    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    
    public function currentRental()
    {
        return $this->hasOne(Rental::class)
            ->where('status', 'active');
    }

    public function getCurrentRentalShareAttribute()
    {
        return $this->currentRental
            ? $this->currentRental->participants->sum('share')
            : 0;
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