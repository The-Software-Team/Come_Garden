<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeedBatch;
use App\Models\Member;

class SeedBatchSeeder extends Seeder
{
    public function run(): void
    {
        // ------------------------------------
        // 1. FIND SPECIAL MEMBER
        // ------------------------------------
        $member = Member::where('email', 'saged@example.com')->first();

        if (!$member) {
            $member = Member::create([
                'name' => 'Saged Nader',
                'email' => 'saged@example.com',
                'password' => bcrypt('password'),
            ]);
        }

       SeedBatch::factory()->count(5)->create([
            'owner_type' => 'inventory',
            'owner_id'   => $member->id,
        ]);

        SeedBatch::factory()->count(15)->create([
            'owner_type' => 'market',
            'owner_id'   => null,
        ]);

        // ensure market variety diversity
        $this->seedMarketStructure();
    }

    private function seedMarketStructure(): void
    {
        $types = ['Wheat', 'Corn', 'Rice', 'Tomato'];

        foreach ($types as $type) {
            SeedBatch::create([
                'owner_type' => 'market',
                'owner_id'   => null,
                'seed_type'  => $type,
                'quantity'   => rand(50, 200),
                'viability'  => rand(60, 100),
                'origin'     => 'Global Market',
                'age'        => rand(1, 8),
            ]);
        }
    }
}