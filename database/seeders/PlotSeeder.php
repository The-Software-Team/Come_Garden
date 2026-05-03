<?php

namespace Database\Seeders;

use App\Models\Plot\Plot;
use Illuminate\Database\Seeder;

class PlotSeeder extends Seeder
{
    public function run(): void
    {
        Plot::factory()
            ->count(20)
            ->create();
    }
}