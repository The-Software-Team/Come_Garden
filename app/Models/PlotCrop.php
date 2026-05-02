<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlotCrop extends Model
{
    use HasFactory;

    protected $fillable = [
        'plot_id',
        'user_id',
        'type',
        'stage',
        'planted_at',
    ];

    protected $casts = [
        'planted_at' => 'datetime',
    ];

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }

    public function user()
    {
        return $this->belongsTo(Member::class);
    }


    // helpers
    public function isSeed()
    {
        return $this->stage === 'seed';
    }

    public function isGrowing()
    {
        return $this->stage === 'growing';
    }

    public function isReady()
    {
        return $this->stage === 'harvest';
    }

    public function advanceStage()
    {
        $stages = ['seed', 'growing', 'harvest'];

        $currentIndex = array_search($this->stage, $stages);

        if ($currentIndex !== false && isset($stages[$currentIndex + 1])) {
            $this->stage = $stages[$currentIndex + 1];
            $this->save();
        }
    }
}