<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\{JsonResponse, Request};

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(ListUserRequest $request): JsonResource
    {
        $per_page = $request->get('per_page') ?? 10;

        return UserResource::collection(User::query()->paginate($per_page));
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }
}
