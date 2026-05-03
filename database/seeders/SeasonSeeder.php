<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure only ONE active season
        Season::factory()->create([
            'name' => 'Spring 2026',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
        ]);

        // Historical seasons
        Season::factory()
            ->count(3)
            ->create([
                'status' => 'finished',
            ]);

        // Future seasons
        Season::factory()
            ->count(2)
            ->create([
                'status' => 'upcoming',
            ]);
    }
}