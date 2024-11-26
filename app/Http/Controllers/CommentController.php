<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index']);
    }

    public function index(Post $post)
    {
        return response()->json($post->comments()->with('user:id,name')->get());
    }

    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user()->id
        ]);

        return response()->json($comment->load('user:id,name'), 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        
        $comment->delete();
        return response()->json(null, 204);
    }
}
