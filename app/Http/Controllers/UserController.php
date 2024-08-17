<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::with('posts.category')->get();

        return (UserResource::collection($users))->additional([
            'error'   => false,
            'message' => 'Users retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        if (User::query()->where('email', $request->email)->exists()) {
            return response()->json([
                'error'   => true,
                'message' => 'User already exists',
            ], 400);
        }

        $user = User::create($request->validated());

        return (new UserResource($user->load('posts.category')))->additional([
            'error'   => false,
            'message' => 'User created successfully',
        ])->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return (new UserResource($user->load('posts.category')))->additional([
            'error'   => false,
            'message' => 'User retrieved successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        if ($request->email != $user->email && User::query()->where('email', $request->email)->exists()) {
            return response()->json([
                'error'   => true,
                'message' => 'User already exists',
            ], 400);
        }

        $user->update($request->validated());

        return (new UserResource($user->load('posts.category')))->additional([
            'error'   => false,
            'message' => 'User updated successfully',
        ])->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'error'   => false,
            'message' => 'User deleted successfully',
        ], 200);
    }
}
