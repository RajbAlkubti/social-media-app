<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $follower = User::inRandomOrder()->first();
        $following = User::inRandomOrder()->first();

        // Ensure a user doesn't follow themselves
        while ($follower && $following && $follower->id === $following->id) {
            $following = User::inRandomOrder()->first();
        }

        return [
            'follower_id' => $follower?->id,
            'following_id' => $following?->id,
        ];
    }
}
