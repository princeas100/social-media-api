<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments()->with('user')->get();

        return response()->json([
            'message' => 'Comments for post ' . $id,
            'comments' => $comments // This should be replaced with actual comments from the database
        ]);
    }

    public function store(Request $request, $post_id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        Comment::create([
            'post_id' => $post_id,
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        return response()->json([
            'message' => 'Comment created successfully'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        $comment = Comment::findOrFail($id);
        $comment->update([
            'content' => $request->content
        ]);

        return response()->json([
            'message' => 'Comment updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
