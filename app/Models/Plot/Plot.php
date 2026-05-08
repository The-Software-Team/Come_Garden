<?php

namespace App\Models\Plot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Rental;
use App\Models\RentalApplication;
use App\Models\Activity;

use App\Models\Plot\PlotInfection;
use App\Models\Plot\PlotCrop;

class Plot extends Model
{
    protected $fillable = [
        'size',
        'x',
        'y',
        'width',
        'height',
        'area',
        'x_min',
        'x_max',
        'y_min',
        'y_max',
        'sun_profile',
        'status',
        'soil_quality',
        'infection_status',
        'infection_type',
        'infection_date',
    ];

    protected $casts = [
        'x'                => 'float',
        'y'                => 'float',
        'width'            => 'float',
        'height'           => 'float',
        'area'             => 'float',
        'x_min'            => 'float',
        'x_max'            => 'float',
        'y_min'            => 'float',
        'y_max'            => 'float',
        'infection_status' => 'boolean',
        'infection_date'   => 'date',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * All plots that are neighbors of this plot (pivot: direction FROM this plot).
     */
    public function neighbors(): BelongsToMany
    {
        return $this->belongsToMany(
            Plot::class,
            'plot_neighbors',
            'plot_id',
            'neighbor_plot_id'
        )->withPivot('direction')->withTimestamps();
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
    // ── Helpers ──────────────────────────────────────────────────────────────

    public function getBoundaryAttribute(): array
    {
        return [
            'x_min' => $this->x_min,
            'x_max' => $this->x_max,
            'y_min' => $this->y_min,
            'y_max' => $this->y_max,
        ];
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

    // public function neighbors()
    // {
    //     return $this->belongsToMany(Plot::class, 'plot_neighbors', 'plot_id', 'neighbor_id');
    // }

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