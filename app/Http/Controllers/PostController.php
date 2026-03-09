<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of posts with author and comments.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Load with relations to avoid N+1 problem
        $posts = Post::with(['user', 'comments'])->get();

        return response()->json($posts);
    }

    /**
     * Create a new post and associate with tags using transaction.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $post = Post::create([
                'title' => $request->input('title'),
                'body' => $request->input('body'),
                'user_id' => auth()->id(),
            ]);

            if ($request->has('tags')) {
                $post->tags()->sync($request->input('tags'));
            }

            return response()->json($post, 201);
        });
    }
}
