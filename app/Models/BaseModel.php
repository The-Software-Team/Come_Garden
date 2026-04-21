<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BaseModel extends Model
{
    use HasFactory;

    protected $guarded = []; // safety comes from fillable, not this

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}