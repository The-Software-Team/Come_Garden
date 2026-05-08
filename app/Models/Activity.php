<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'plot_id',
        'type',
        'crop',
        'member',
        'fertilizer'
    ];
}
