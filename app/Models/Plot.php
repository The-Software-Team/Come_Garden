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
    ];

    public function rental()
    {
        return $this->hasOne(Rental::class);
    }

    public function applications()
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

    // public function infections()
    // {
    //     return $this->hasMany(PlotInfection::class);
    // }
}