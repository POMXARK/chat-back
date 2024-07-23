<?php

namespace App\UseCases\Message;

use App\Jobs\SendMessage;
use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\Message;
use Symfony\Component\HttpFoundation\Response;

/**
 * Создать сообщение и отправить событие.
 */
class MessageCommandCreate
{
    public function handle(Chat $chat, array $data): array
    {
        if (ChatUsers::query()->where('chat_id', $chat->id)->where('user_id', auth()->user()->id)->get()) {
            $message = Message::query()->create([
                'user_id' => auth()->user()->id,
                'text' => $data['text'],
                'chat_id' => $chat->id,
            ]);
            SendMessage::dispatch($message);

            return [[
                'success' => true,
                'message' => "Message created and job dispatched.",
            ]];
        }

        return [Response::HTTP_FORBIDDEN];
    }
}
