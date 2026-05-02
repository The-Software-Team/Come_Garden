<?php

namespace Database\Seeders;

use App\Models\Plot;
use Illuminate\Database\Seeder;
use Database\Factories\PlotFactory;

class PlotSeeder extends Seeder
{
    public function run(): void
    {
        // reset grid state
        PlotFactory::new(); // ensure class loaded

        Plot::factory()
            ->count(20)
            ->create();
    }
}