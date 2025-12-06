<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($postSlug)
    {
        $post = Post::where('slug', $postSlug)->firstOrFail();
        
        $comments = Comment::where('post_id', $post->id)
            ->approved()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'comments' => $comments,
            'count' => $comments->count()
        ]);
    }

    public function store(Request $request, $postSlug)
    {
        $post = Post::where('slug', $postSlug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'content' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comment = Comment::create([
            'post_id' => $post->id,
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'content' => $request->content,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment submitted! It will appear after approval.',
            'data' => $comment
        ], 201);
    }

    // Admin methods
    public function adminIndex()
    {
        $comments = Comment::with('post')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'comments' => $comments,
            'stats' => [
                'total' => Comment::count(),
                'pending' => Comment::pending()->count(),
                'approved' => Comment::approved()->count(),
            ]
        ]);
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approve();

        return response()->json([
            'success' => true,
            'message' => 'Comment approved',
            'data' => $comment
        ]);
    }

    public function markAsSpam($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->markAsSpam();

        return response()->json([
            'success' => true,
            'message' => 'Comment marked as spam'
        ]);
    }

    public function delete($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted'
        ]);
    }
}