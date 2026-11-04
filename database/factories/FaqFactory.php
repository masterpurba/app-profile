<?php

namespace Database\Factories;

use App\Models\Faq;
use App\Models\QuestionTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    public function definition(): array
    {
        return [
            'topic_id' => QuestionTopic::factory(),
            'question' => fake()->sentence(6).'?',
            'answer' => '<p>'.fake()->paragraph().'</p>',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
