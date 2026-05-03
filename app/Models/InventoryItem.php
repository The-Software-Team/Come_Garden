<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'reorder_threshold',
        'unit',
        'type', // seedbank / tool / consumable
        'status',
    ];

    // helper
    public function needsReorder(): bool
    {
        return $this->quantity <= $this->reorder_threshold;
    }
}