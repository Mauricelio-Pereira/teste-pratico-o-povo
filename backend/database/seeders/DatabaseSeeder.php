<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (!User::where('email', 'opovo@example.com')->exists()) {
            User::factory()->create([
                'email' => 'opovo@example.com',
                'name'  => 'O Povo',
            ]);
        }

        $UserNeeded = 10 - User::count();
        if ($UserNeeded > 0) {
            User::factory($UserNeeded)->create();
        }

        $postNeeded = 20 - Post::count();
        if ($postNeeded > 0) {
            Post::factory($postNeeded)->create();
        }
    }
}
