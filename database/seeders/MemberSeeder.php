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
            ->create()
            ->each(function ($member) {

                Wallet::create([
                    'member_id' => $member->id,
                    'type' => 'seedbank',
                    'balance' => 100, // starting credits
                ]);
            });
    }
}