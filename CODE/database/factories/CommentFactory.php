<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;
use App\Models\Post;

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
        $post = Post::inRandomOrder()->first();

        // Get a user that is not the post's author
        $user = User::where('id', '!=', $post->user_id)->inRandomOrder()->first();

        return [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => fake()->sentence(10),
        ];
    }
}
