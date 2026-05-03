<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Wallet;
use App\Models\Role;


use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole  = Role::create(['name' => 'user']);

        Member::factory()
            ->count(10)
            ->has(Wallet::factory()->state([
                'balance' => 200, // main wallet (default)
            ]))
            ->has(Wallet::factory()->seedbank()->state([
                'balance' => 100,
            ]))
            ->create()
            ->each(function ($member) use ($userRole) {
                $member->roles()->attach($userRole->id);
            });

        $admin = Member::factory()->create([
            'name' => 'Saged Nader',
            'email' => 'saged@example.com',
            'password' => bcrypt('password'),
        ]);

        $admin->roles()->attach($adminRole->id);

        Wallet::factory()->create([
            'member_id' => $admin->id,
            'balance' => 1000, // main
        ]);

        Wallet::factory()->seedbank()->create([
            'member_id' => $admin->id,
            'balance' => 500,
        ]);
    }
}