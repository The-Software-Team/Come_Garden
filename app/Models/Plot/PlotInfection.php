<?php

namespace App\Models\Plot;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotInfection extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'type',
        'severity',
        'infection_date',
        'resolved_at',
    ];

    protected $casts = [
        'infection_date' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    // helpers
    public function isActive()
    {
        return is_null($this->resolved_at);
    }

    public function isResolved()
    {
        return !is_null($this->resolved_at);
    }

    public function resolve()
    {
        $this->resolved_at = now();
        $this->save();
    }

    public function isSevere()
    {
        return $this->severity === 'high';
    }
}