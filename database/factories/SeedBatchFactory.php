<?php

namespace Database\Factories;

use App\Models\SeedBatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeedBatchFactory extends Factory
{
    protected $model = SeedBatch::class;

    public function definition(): array
    {
        return [
            'owner_type' => $this->faker->randomElement(['inventory', 'market']),
            'owner_id'   => null, // overridden in seeder
            'seed_type'  => $this->faker->randomElement([
                'Wheat',
                'Corn',
                'Rice',
                'Tomato',
                'Barley',
                'Sunflower'
            ]),
            'quantity'   => $this->faker->numberBetween(10, 200),
            'viability'  => $this->faker->numberBetween(50, 100),
            'origin'     => $this->faker->city(),
            'age'        => $this->faker->numberBetween(1, 10),
        ];
    }
}