<?php

namespace Database\Factories\ToolLibrary;

use App\Models\ToolLibrary\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    protected $model = Tool::class;

    public function definition(): array
    {
        static $index = 1;
        
        $names = [
            'Drill',
            'Hammer',
            'Chainsaw',
            'Lawn Mower',
            'Shovel',
            'Rake',
            'Pressure Washer'
        ];
    
        return [
            'name' => $names[$index++ % count($names)] . ' #' . $index,
            'status' => fake()->randomElement(['available', 'in_use', 'maintenance']),
            'usage_status' => fake()->randomElement(['low', 'medium', 'high']),
            'total_usage_hours' => fake()->numberBetween(0, 200),
            'maintenance_threshold_hours' => fake()->randomElement([80, 100, 150]),
            'is_active' => true,
        ];
    
    }
}