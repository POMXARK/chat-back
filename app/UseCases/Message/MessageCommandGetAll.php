<?php

namespace App\UseCases\Message;

use App\Http\Resources\MessageCollection;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Получить все сообщения из чатов авторизованного пользователя постранично.
 */
class MessageCommandGetAll
{
    const COUNT_MESSAGES = 20;

    public function handle(Chat $chat): ResourceCollection
    {
        $messages = Message::query()->with('user')->withWhereHas('chat.users', function ($query) use ($chat) {
            $query->where('chat_id', $chat->id);
            $query->where('user_id', auth()->user()->id);
        })->orderByDesc('created_at')->paginate(self::COUNT_MESSAGES);

        return MessageCollection::make($messages);
    }
}
