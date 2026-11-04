<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Slide>
 */
class SlideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(8),
            'image' => 'slides/slide-'.fake()->numberBetween(1, 5).'.jpg',
            'link_url' => fake()->url(),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(30)->toDateString(),
        ];
    }
}
