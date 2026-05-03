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
        $adminRole = Role::Create(['name' => 'admin']);
        $userRole =  Role::Create(['name' => 'user']);

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
    }
}