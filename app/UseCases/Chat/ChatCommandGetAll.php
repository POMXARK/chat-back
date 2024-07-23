<?php

namespace App\UseCases\Chat;

use App\Models\Chat;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Получить все чаты авторизованного пользователя постранично.
 */
class ChatCommandGetAll
{
    const COUNT_CHATS = 20;
    public function handle():LengthAwarePaginator
    {
        return Chat::query()->withWhereHas('users', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->paginate(self::COUNT_CHATS);
    }
}
