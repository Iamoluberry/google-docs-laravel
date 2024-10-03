<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\permissionRole;
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
        // User::factory(10)->create();


        $this->call([
           RolesSeeder::class,

            PermissionSeeder::class,
            UserSeeder::class,
            PostSeeder::class,
           CommentSeeder::class,
        ]);
    }
}
