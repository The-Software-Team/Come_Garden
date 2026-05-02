<?php

namespace Database\Factories;

use App\Models\Plot;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlotFactory extends Factory
{
    protected $model = Plot::class;

    private static int $gridX = 0;
    private static int $gridY = 0;
    private static int $maxX = 5;

    public function definition(): array
    {
        // GRID POSITIONING (NOT RANDOM)
        $x = self::$gridX * 10;
        $y = self::$gridY * 10;

        self::$gridX++;

        if (self::$gridX >= self::$maxX) {
            self::$gridX = 0;
            self::$gridY++;
        }

        $size = $this->faker->randomElement(['small', 'large']);

        // consistent sizing rules
        $dimensions = $size === 'large'
            ? [10, 10]
            : [5, 5];

        [$width, $height] = $dimensions;

        return [
            'size' => $size,

            'x' => $x,
            'y' => $y,

            'width' => $width,
            'height' => $height,

            'area' => $width * $height,

            'status' => $this->faker->randomElement([
                'available',
                'rented',
                'available'
            ]),

            'soil_quality' => $this->faker->randomElement([
                'poor',
                'normal',
                'rich'
            ]),
        ];
    }
}