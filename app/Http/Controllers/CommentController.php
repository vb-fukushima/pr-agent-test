<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    // BAD: hardcoded magic numbers
    private $maxLength = 500;
    private $minLength = 5;
    private $maxCommentsPerUser = 10;

    public function index(int $postId): JsonResponse
    {
        // BAD: N+1 problem - accessing user inside loop will fire extra queries
        $comments = Comment::where('post_id', $postId)->get();

        $result = [];
        foreach ($comments as $comment) {
            $result[] = [
                'id' => $comment->id,
                'body' => $comment->body,
                'author' => $comment->user->name,
            ];
        }

        return response()->json($result);
    }

    public function store(Request $request, int $postId): JsonResponse
    {
        $body = $request->input('body');

        // BAD: SQL injection - user input directly in raw query
        $exists = DB::select("SELECT * FROM posts WHERE id = " . $postId . " AND deleted_at IS NULL");
        if (empty($exists)) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        // BAD: no validation, no auth check
        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => $request->input('user_id'),
            'body' => $body,
        ]);

        return response()->json($comment, 201);
    }

    public function destroy(int $id): JsonResponse
    {
        // BAD: no authorization check - any user can delete any comment
        $comment = Comment::find($id);
        $comment->delete();

        return response()->json(null, 204);
    }
}
