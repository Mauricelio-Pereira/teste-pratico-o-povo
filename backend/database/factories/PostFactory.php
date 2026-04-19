<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'          => fake()->sentence(6),
            'content'        => fake()->paragraphs(3, true),
            'author_user_id' => User::inRandomOrder()->value('id'),
        ];
    }
}
