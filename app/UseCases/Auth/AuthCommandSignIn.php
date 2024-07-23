<?php

namespace App\UseCases\Auth;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Авторизация пользователя.
 */
class AuthCommandSignIn
{
    public function handle(array $data): array
    {
        $user = User::query()->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [[
                'msg' => 'incorrect username or password'
            ], Response::HTTP_UNAUTHORIZED];
        }

        $token = $user->createToken('apiToken')->plainTextToken;

        return [[
            'user' => UserResource::make($user),
            'token' => $token
        ], Response::HTTP_OK];
    }
}
