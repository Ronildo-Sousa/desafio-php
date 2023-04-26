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

    public function show($id): JsonResponse
    {
        //
    }

    public function update(Request $request, $id): JsonResponse
    {
        //
    }

    public function destroy($id): JsonResponse
    {
        //
    }
}
