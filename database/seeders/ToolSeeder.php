<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolLibrary\Tool;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [
            [
                'name' => 'Drill #1',
                'status' => 'available',
                'usage_status' => 'low',
                'total_usage_hours' => 10,
                'maintenance_threshold_hours' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Hammer #2',
                'status' => 'in_use',
                'usage_status' => 'medium',
                'total_usage_hours' => 45,
                'maintenance_threshold_hours' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Chainsaw #3',
                'status' => 'maintenance',
                'usage_status' => 'high',
                'total_usage_hours' => 120,
                'maintenance_threshold_hours' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Lawn Mower #4',
                'status' => 'available',
                'usage_status' => 'medium',
                'total_usage_hours' => 60,
                'maintenance_threshold_hours' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Shovel #5',
                'status' => 'available',
                'usage_status' => 'low',
                'total_usage_hours' => 5,
                'maintenance_threshold_hours' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Rake #6',
                'status' => 'in_use',
                'usage_status' => 'medium',
                'total_usage_hours' => 30,
                'maintenance_threshold_hours' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Pressure Washer #7',
                'status' => 'maintenance',
                'usage_status' => 'high',
                'total_usage_hours' => 140,
                'maintenance_threshold_hours' => 150,
                'is_active' => true,
            ],
        ];

        foreach ($tools as $tool) {
            Tool::create($tool);
        }
    }
}