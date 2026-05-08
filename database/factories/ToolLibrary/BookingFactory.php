<?php

namespace Database\Factories\ToolLibrary;

use App\Models\ToolLibrary\Booking;
use App\Models\ToolLibrary\Tool;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $start = now()->subDays(rand(0, 10));
        $end = (clone $start)->addHours(rand(1, 8));

        return [
            'tool_id' => Tool::factory(),
            'member_id' => Member::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'actual_return_time' => null,
            'status' => fake()->randomElement([
                'active',
            ]),
            'qr_token' => fake()->uuid(),
            'picked_up_at' => $start,
            'returned_scanned_at' => null,
            'cleaned_at' => null,
        ];
    }

    public function completed(): self
    {
        return $this->state([
            'status' => 'completed',
            'actual_return_time' => now(),
            'cleaned_at' => now(),
        ]);
    }

    public function overdue(): self
    {
        return $this->state([
            'status' => 'overdue',
            'actual_return_time' => now()->addHours(3),
        ]);
    }
}