<?php

namespace Database\Seeders;

use Database\Seeders\MemberSeeder;
use Database\Seeders\PlotSeeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            MemberSeeder::class,
            PlotSeeder::class
        ]);
    }
}