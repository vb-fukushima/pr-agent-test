<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // BAD: N+1 problem - calling all() without with(). 
        // Accessing user or comments in a loop will trigger multiple queries.
        $posts = Post::all();

        return response()->json($posts);
    }

    /**
     * Create a new post and associate with tags.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // BAD: Missing transaction for multiple table updates.
        // If tag creation fails, the post remains.
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => auth()->id(),
        ]);

        if ($request->has('tags')) {
            // Assume tags are created/synced here
            foreach ($request->input('tags') as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $post->tags()->attach($tag->id);
            }
        }

        return response()->json($post, 201);
    }
}
