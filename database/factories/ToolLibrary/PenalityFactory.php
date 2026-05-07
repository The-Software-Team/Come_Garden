<?php

namespace Database\Factories\ToolLibrary;

use App\Models\ToolLibrary\Penalty;
use App\Models\ToolLibrary\Booking;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenaltyFactory extends Factory
{
    protected $model = Penalty::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'booking_id' => Booking::factory(),
            'type' => fake()->randomElement(['service', 'fine']),
            'severity' => fake()->randomElement(['low', 'medium', 'high']),
            'amount' => fake()->randomFloat(2, 1, 100),
            'status' => 'pending',
            'resolved' => false,
            'reason' => fake()->sentence(),
        ];
    }
}