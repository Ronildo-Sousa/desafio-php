<?php

namespace App\Actions\Users;

use App\Entities\UserDTO;

class LoginUser
{
    public static function run(string $email, string $password): ?UserDTO
    {
        $credentials = [
            'email'    => $email,
            'password' => $password,
        ];

        if (auth()->attempt($credentials)) {
            $user = request()->user();
            auth()->login($user);

            return UserDTO::from([
                'name'     => $user->name,
                'email'    => $user->email,
                'password' => $user->password,
                'is_admin' => $user->is_admin,
                'api_key'  => $user->api_key,
                'token'    => $user->handleTokens(),
            ]);
        }

        return null;
    }
}
