<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        $posts = Post::with(['user:id,name', 'comments'])->latest()->paginate(10);
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post = $request->user()->posts()->create($request->all());
        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post->load(['user:id,name', 'comments.user:id,name']));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $post->update($request->all());
        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        
        $post->delete();
        return response()->json(null, 204);
    }
}
