<?php

namespace App\Http\Controllers;

use App\Http\Requests\{ListUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\{JsonResponse};
use Symfony\Component\HttpFoundation\Response;

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

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
