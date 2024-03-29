<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UserSeeder::class,
            StatusSeeder::class,
            CommentSeeder::class,
            LikeStatusSeeder::class,
            LikeCommentSeeder::class,
            FriendshipSeeder::class
        ]);

        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
