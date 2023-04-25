<?php

namespace App\Http\Controllers;

use App\Actions\Users\{CreateUser, LoginUser};
use App\Entities\UserDTO;
use App\Http\Requests\LoginUserRequest;
use Illuminate\Http\{JsonResponse, Request};
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = LoginUser::run(
            $request->validated('email'),
            $request->validated('password')
        );

        if (!$user) {
            return response()->json(['message' => 'The credentials dont matches'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(['user' => $user]);
    }

    public function register(Request $request): JsonResponse
    {
        $user = CreateUser::run(UserDTO::from($request));

        return response()->json([
            'user' => $user,
        ], Response::HTTP_CREATED);
    }

    public function registerAdmin(Request $request): JsonResponse
    {
        $user = CreateUser::run(UserDTO::from($request), true);

        return response()->json([
            'user' => $user,
        ], Response::HTTP_CREATED);
    }
}
