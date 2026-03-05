<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->latest()->get();

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $posts,
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'image' => $request->hasFile('image') ? $request->file('image')->store('posts', 'public') : null,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    public function show($id)
    {
        $post = Post::with('user')->findOrFail($id);

        return response()->json([
            'message' => 'Post retrieved successfully',
            'post' => $post,
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'content' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $post->update([
            'content' => $request->content ?? $post->content,
            'image' => $request->hasFile('image') ? $request->file('image')->store('posts', 'public') : $post->image,
        ]);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);
    }

    public function destroy($id) 
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
