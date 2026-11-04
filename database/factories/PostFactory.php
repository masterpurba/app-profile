<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'summary' => fake()->paragraph(),
            'content' => '<p>'.implode('</p><p>', fake()->paragraphs(3)).'</p>',
            'image' => null,
            'status' => PostStatus::Published,
            'published_at' => now(),
            'view_count' => fake()->numberBetween(10, 500),
            'is_featured' => fake()->boolean(20),
        ];
    }
}
