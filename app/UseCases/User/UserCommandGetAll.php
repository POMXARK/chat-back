<?php

namespace App\UseCases\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

/**
 * Получить всех пользователей постранично.
 */
class UserCommandGetAll
{
    const COUNT_USERS = 20;

    public function handle():array
    {
        return [[
            'users' => UserResource::collection(
                User::query()
                    ->select(['id', 'email', 'last_name', 'first_name'])
                    ->paginate(self::COUNT_USERS)
            ),
        ], Response::HTTP_OK];
    }
}
