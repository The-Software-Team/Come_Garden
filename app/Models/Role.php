<?php

namespace App\Models;

use App\Models\Member;

class Role extends BaseModel
{
    protected $fillable = [
        'name',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_role');
    }
}