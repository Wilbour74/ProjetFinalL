<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Republication>
 */
class RepublicationFactory extends Factory
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
            'user_id' => $user_ids->random()->id,
            'post_id' => $posts_ids->random()->id
        ];
    }
}
