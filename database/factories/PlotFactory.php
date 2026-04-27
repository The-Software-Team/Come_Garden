<?php

namespace Database\Factories;

use App\Models\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlotFactory extends Factory
{
    protected $model = Plot::class;

    public function definition(): array
    {
        $width = $this->faker->numberBetween(5, 20);
        $height = $this->faker->numberBetween(5, 20);

        return [
            'size' => $this->faker->randomElement(['small', 'large']),

            'x' => $this->faker->numberBetween(0, 100),
            'y' => $this->faker->numberBetween(0, 100),

            'width' => $width,
            'height' => $height,

            'area' => $width * $height,

            'status' => 'available',

            'soil_quality' => $this->faker->randomElement([
                'poor',
                'normal',
                'rich'
            ]),
        ];
    }

    /**
     * Helpful state for testing rental edge cases
     */
    public function small(): static
    {
        return $this->state(fn () => [
            'size' => 'small',
            'width' => 5,
            'height' => 5,
            'area' => 25,
        ]);
    }

    public function large(): static
    {
        return $this->state(fn () => [
            'size' => 'large',
            'width' => 20,
            'height' => 20,
            'area' => 400,
        ]);
    }

    public function richSoil(): static
    {
        return $this->state(fn () => [
            'soil_quality' => 'rich',
        ]);
    }
}