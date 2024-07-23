<?php

namespace App\UseCases\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Регистрация пользователя.
 */
class AuthCommandSignUp
{
    public function handle(array $data): array
    {
        $user = User::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        $token = $user->createToken('apiToken')->plainTextToken;

        return [[
            'user' => UserResource::make($user),
            'token' => $token
        ], Response::HTTP_CREATED];
    }
}
