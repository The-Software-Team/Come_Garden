<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class MemberFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    # protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */

//     public function admin(): static
//     {
//         return $this->state(fn () => [
//             'is_admin' => true,
//         ]);
//     } 
}
