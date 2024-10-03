<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddUserToPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $newPost = Post::create([
            "title" => $request["title"],
            "body" => $request["body"],
        ]);

        //attach new post to the auth user creating the post
        Auth::user()->posts()->attach($newPost);

        return response()->json([
            "message" => "Post created",
            "post" => $newPost
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!$post){
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $updatedPost = $post->update([
            "title" => $request["title"] ?? $post->title,
            "body" => $request["body"] ?? $post->body,
        ]);

        return response()->json([
            "message" => "Post updated",
            "post" => $updatedPost
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!$post){
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }

        foreach ($post->users as $user){

            if ($user->is(Auth::user()) && $post->users->count() === 1){
                $post->delete();
                return response()->json([
                    "message" => "Post deleted"
                ], 200);
            } else if($user->is(Auth::user()) && $post->users->count() > 1){

                $post->users()->detach($user->id);

                return response()->json([
                    "message" => "Unable to delete post, but you've exited this post, to regain access contact post admins"
                ], 200);
            }
        }

        return response()->json([
            "message" => "You dont have access to this post"
        ], 403);
    }

    public function exitPost(Post $post)
    {
        if (!$post){
            return response()->json([
                "message" => "Post not found"
            ], 404);
        }

        foreach ($post->users as $user){

            if ($user->is(Auth::user()) && $post->users->count() === 1){
                $post->delete();

                return response()->json([
                    "message" => "You've exited this post and lost the post!"
                ], 200);

            } else if($user->is(Auth::user()) && $post->users->count() > 1){
                $post->users()->detach($user->id);
                return response()->json([
                    "message" => "You've exited this post successfully, contact other collaborators to regain access!"
                ], 200);
            }
        }

        return response()->json([
            "message" => "You dont have access to this post"
        ], 403);
    }

    public function addUserToPost(StoreAddUserToPostRequest $request, Post $post)
    {
        foreach ($post->users as $user) {
            if ($user->is(Auth::user())) {

                $newUserEmail = $request['email'];

                $newUser = User::where("email", $newUserEmail)->first();

                if (!$newUser) {
                    return response()->json([
                        "message" => $newUserEmail . " does not exist"
                    ]);
                }

                if ($newUser->exists() == true && $newUser->role_id === 3){
                    return response()->json([
                        "message" => $newUserEmail . " needs to be verified to join post"
                    ]);
                }
                else if ($newUser->exists() == true && $newUser->role_id === 1 || $newUser->role_id === 2) {

                    foreach ($post->users as $postUsers) {

                        if ($postUsers->is($newUser)){
                            return response()->json([
                                "message" => $newUserEmail . " added to post already!"
                            ]);
                        }

                        PostUser::create([
                            "post_id" => $post->id,
                            "user_id" => $newUser->id,
                        ]);

                        return response()->json([
                            "message" => $newUser->name . " added to " . $post->title . " post successfully"
                        ], 201);
                    }
                }
            }
        }

        return response()->json([
            "message" => "You dont belong to this post"
        ], 403);
    }
}
