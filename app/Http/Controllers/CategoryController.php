<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('posts.user')->get();

        return (CategoryResource::collection($categories))->additional([
            'error'   => false,
            'message' => 'Categories retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        if (Category::query()->where('name', $request->name)->exists()) {
            return response()->json([
                'error'   => true,
                'message' => 'Category already exists',
            ], 400);
        }

        $category = Category::create($request->validated());

        return (new CategoryResource($category->load('posts.user')))->additional([
            'error'   => false,
            'message' => 'Category created successfully',
        ])->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return (new CategoryResource($category->load('posts.user')))->additional([
            'error'   => false,
            'message' => 'Category retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        if ($request->name != $category->name && Category::query()->where('name', $request->name)->exists()) {
            return response()->json([
                'message' => 'Category already exists',
            ], 400);
        }

        $category->update($request->validated());

        return (new CategoryResource($category->load('posts.user')))->additional([
            'error'   => false,
            'message' => 'Category updated successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'error'   => false,
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
