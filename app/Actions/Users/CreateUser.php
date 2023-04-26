<?php

namespace App\Actions\Users;

use App\Entities\UserDTO;
use App\Models\User;

class CreateUser
{
    public static function run(UserDTO $user, bool $is_admin = false): UserDTO
    {
        $user             = $user->toArray();
        $user['is_admin'] = $is_admin;

        $newUser = User::query()
            ->create($user);

        return UserDTO::from([
            'name'     => $newUser->name,
            'email'    => $newUser->email,
            'password' => $newUser->password,
            'is_admin' => $newUser->is_admin,
            'token'    => $newUser->handleTokens(),
        ]);
    }
}
