<?php

namespace Database\Factories;

use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', 'now');
        $end = (clone $start)->modify('+3 months');

        return [
            'name' => 'Season ' . $this->faker->numberBetween(1, 10),
            'start_date' => $start,
            'end_date' => $end,
            'status' => 'upcoming',
        ];
    }

    public function active()
    {
        return $this->state(fn () => [
            'status' => 'active',
        ]);
    }
}