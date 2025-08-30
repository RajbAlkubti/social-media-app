<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Follow;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'test',
            'username' => 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('123456789'), 
            'user_type' => 'user',
            'bio' => 'This is the user account.',
            'profile_picture' => "assets/profilePicture/DefaultPfp.jpg",
        ]);

        User::factory()->count(20)->create();
        Post::factory()->count(40)->create();
        Comment::factory()->count(50)->create();
        Follow::factory()->count(10)->create();

        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456789'),
            'user_type' => 'admin',
            'bio' => 'This is the admin account.',
            'profile_picture' => "assets/profilePicture/DefaultPfp.jpg", 
        ]);
    }
}
