<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────
        // NORMAL STOCK (safe items)
        // ─────────────────────────────
        InventoryItem::factory()->count(10)->create();

        // ─────────────────────────────
        // ALERT ITEMS (LOW STOCK → triggers reorder_required)
        // ─────────────────────────────
        InventoryItem::create([
            'name' => 'Premium Seed Pack',
            'quantity' => 3,
            'reorder_threshold' => 10,
            'unit' => 'pack',
            'type' => 'seed',
            'status' => 'active',
        ]);

        InventoryItem::create([
            'name' => 'Organic Fertilizer Bag',
            'quantity' => 2,
            'reorder_threshold' => 15,
            'unit' => 'kg',
            'type' => 'fertilizer',
            'status' => 'active',
        ]);

        InventoryItem::create([
            'name' => 'Basic Tool Kit',
            'quantity' => 1,
            'reorder_threshold' => 5,
            'unit' => 'set',
            'type' => 'tool',
            'status' => 'active',
        ]);

        InventoryItem::create([
            'name' => 'Greenhouse Nutrients',
            'quantity' => 0,
            'reorder_threshold' => 8,
            'unit' => 'bottle',
            'type' => 'consumable',
            'status' => 'active',
        ]);
    }
}