<?php

namespace Database\Factories\Market;

use Illuminate\Database\Eloquent\Factories\Factory;

class CanningSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'scheduled_at' => now()->addDays(5),
            'max_members' => fake()->numberBetween(3, 15),
            'location' => fake()->address(),
            'produce_target' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }
}