<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Wallet;

use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        Member::factory()
        ->count(10)
        ->has(
            Wallet::factory()->state([
            'type' => 'seedbank',
            'balance' => 100,
            ])
        )
        ->has(
            Wallet::factory()->state([
            'type' => 'credits',
            'balance' => 0,
            ])
        )
        ->create();
    }
}