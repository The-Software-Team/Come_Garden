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
            'end_date'   => $end,

            'status' => 'active',
        ];
    }

    /**
     * Active season state (useful for rental tests)
     */
    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'active',
        ]);
    }

    /**
     * Future season state (for renewal tests)
     */
    public function future(): static
    {
        return $this->state(function () {
            $start = now()->addMonth();
            return [
                'start_date' => $start,
                'end_date' => (clone $start)->addMonths(3),
                'status' => 'upcoming',
            ];
        });
    }

    /**
     * Past season state (for end-rental logic)
     */
    public function past(): static
    {
        return $this->state(function () {
            $start = now()->subMonths(4);
            return [
                'start_date' => $start,
                'end_date' => (clone $start)->addMonths(3),
                'status' => 'finished',
            ];
        });
    }
}