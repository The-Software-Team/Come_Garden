<?php

namespace Database\Factories;

use App\Models\Wallet;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'type' => 'seedbank',
            'balance' => $this->faker->numberBetween(0, 10000),
        ];
    }
}