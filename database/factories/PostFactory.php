<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $elements = ['chat.jpg', 'cheval.jpg', 'chevre.jpg', 'chien.jpg', 'elephant.jpg', 'iguane.jpg', 'renard.jpg', 'vache.jpg', 'zebre.jpg'];
        $user_ids = User::all('id');
        return [
            'title' => fake()->name(),
            'content' => fake()->paragraph(),
            'user_id' => $user_ids->random()->id,
            'image' => $elements[array_rand($elements)],
        ];
    }
}
