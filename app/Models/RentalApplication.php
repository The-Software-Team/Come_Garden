<?php
namespace App\Models;

use App\Models\Member;
use App\Models\Plot\Plot;

use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    protected $fillable = [
        'member_id',
        'plot_id',
        'share',
        'auto_renew',
        'status',
        'score',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function plot()
    {
        return $this->belongsTo(Plot::class);
    }
}