<?php

namespace Database\Factories;

use App\Enums\QuestionStatus;
use App\Models\Question;
use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'topic_id' => QuestionTopic::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(5),
            'body' => fake()->paragraph(),
            'status' => QuestionStatus::Pending,
            'ip_address' => '127.0.0.1',
        ];
    }
}
