<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggle($postId)
    {
        $like = Like::where('post_id', $postId)
            ->where('user_id', auth()->id())
            ->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Post unliked successfully']);
        } else {
            Like::create([
                'post_id' => $postId,
                'user_id' => auth()->id()
            ]);
            return response()->json(['message' => 'Post liked successfully']);
        }
    }
}
