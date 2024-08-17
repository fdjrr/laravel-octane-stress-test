<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('category', 'user')->get();

        return (PostResource::collection($posts))->additional([
            'error'   => false,
            'message' => 'Posts retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        if (!Category::find($request->category_id)) {
            return response()->json([
                'error'   => true,
                'message' => 'Category not found',
            ], 404);
        }

        $post = Post::create($request->validated());

        return (new PostResource($post->load('category', 'user')))->additional([
            'error'   => false,
            'message' => 'Post created successfully',
        ])->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): JsonResponse
    {
        return (new PostResource($post->load('category', 'user')))->additional([
            'error'   => false,
            'message' => 'Post retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        if (!Category::find($post->category_id)) {
            return response()->json([
                'error'   => true,
                'message' => 'Category not found',
            ], 404);
        }

        $post->update([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
        ]);

        return (new PostResource($post->load('category', 'user')))->additional([
            'error'   => false,
            'message' => 'Post updated successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'error'   => false,
            'message' => 'Post deleted successfully',
        ], 200);
    }
}
