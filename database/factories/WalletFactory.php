<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'type' => 'main',
            'balance' => $this->faker->numberBetween(0, 10000),
        ];
    }

    public function seedbank()
    {
        return $this->state(fn () => ['type' => 'seedbank']);
    }
}