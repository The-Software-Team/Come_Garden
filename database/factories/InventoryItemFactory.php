<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;

    public function definition(): array
    {
        $types = ['seed', 'tool', 'fertilizer', 'consumable'];

        return [
            'name' => $this->faker->randomElement([
                'Seed Pack A',
                'Seed Pack B',
                'Organic Fertilizer',
                'Watering Can',
                'Soil Mix',
                'Plant Nutrients',
                'Gardening Gloves',
                'Pruning Shears',
            ]),
            'quantity' => $this->faker->numberBetween(5, 100),
            'reorder_threshold' => $this->faker->numberBetween(10, 30),
            'unit' => $this->faker->randomElement(['kg', 'pack', 'pcs', null]),
            'type' => $this->faker->randomElement($types),
            'status' => 'active',
        ];
    }
}