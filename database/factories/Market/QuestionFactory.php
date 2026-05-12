<?php

namespace Database\Factories\Market;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Market\Question;
use App\Models\Member;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'user_id' => Member::factory(),
            'title' => fake()->sentence(6),
            'body' => fake()->paragraph(),
            'is_resolved' => fake()->boolean(20),
        ];
    }
}