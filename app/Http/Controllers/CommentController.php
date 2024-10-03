<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Comment::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Post $post)
    {

        $comment = Comment::create([
           'text' => $request['text'],
           'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return response()->json([
            'message' => 'comment created',
            'comment' => $comment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment, Post $post)
    {
        $comments = $post->comments;

        if (!$comments) {
            return response()->json([
                'message' => 'No comment found'
            ], 404);
        }

        $commentsData = $comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'text' => $comment->text,
                'created_at' => $comment->created_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'data' => $commentsData,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $updatedComment = $request->only('text');

        if (!$updatedComment){
            return response()->json([
                "message" => "Unable to update comment"
            ], 404);
        }

        $comment->update($updatedComment);

        return response()->json([
            'message' => 'comment updated'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([
            'message' => 'comment deleted'
        ], 200);
    }
}