<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Republication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Post::truncate();
        Comment::truncate();
        Republication::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::factory(10)
        ->create();

        Post::factory(50)
        ->create();

        Comment::factory(150)
        ->create();

        Republication::factory(70)
        ->create();
    }
}
