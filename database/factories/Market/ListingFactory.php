<?php

namespace Database\Factories\Market;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market\Listing;
use App\Models\Member;

class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        return [
            'user_id' => Member::factory(),
            'produce_name' => fake()->randomElement([
                'Tomatoes', 'Potatoes', 'Cucumbers', 'Carrots', 'Zucchini'
            ]),
            'type' => fake()->randomElement(['standard', 'flash', 'gift']),
            'quantity_kg' => fake()->randomFloat(1, 1, 20),
            'price' => fake()->randomFloat(2, 0, 50),
            'description' => fake()->sentence(),
            'pickup_location' => fake()->city(),
            'pickup_window_hours' => fake()->numberBetween(1, 12),
            'expires_at' => now()->addDays(3),
            'status' => 'active',
            'allergen_flags' => null,
            'quality_score' => fake()->randomFloat(1, 3, 5),
        ];
    }
}