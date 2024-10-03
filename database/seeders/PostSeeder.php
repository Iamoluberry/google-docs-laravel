<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Post::factory(3)->create();

        $posts = Post::all();

        $adminUser = User::where('email', 'olanrewajuakilu@gmail.com')->first();

        foreach ($posts as $post) {
//            PostUser::create([
//                'post_id' => $post->id,
//                'user_id' => $adminUser->id,
//            ]);

            $adminUser->posts()->attach($post);
        }

        $user = User::where('email', 'cocomelon@gmail.com')->first();
        foreach ($posts as $post) {
//            PostUser::create([
//                'post_id' => $post->id,
//                'user_id' => $user->id,
//            ]);

            $user->posts()->attach($post);
        }
    }
}
