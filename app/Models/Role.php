<?php

namespace App\Models;

use App\Models\Member;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_role');
    }
}