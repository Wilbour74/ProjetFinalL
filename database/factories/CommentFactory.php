<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_ids = User::all('id');
        $posts_ids = Post::all('id');
        return [
            'content' => fake()->paragraph(),
            'user_id' => $user_ids->random()->id,
            'post_id' => $posts_ids->random()->id
        ];
    }
}
