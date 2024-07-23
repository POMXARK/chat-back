<?php

namespace App\UseCases\Chat;

use App\Models\Chat;
use App\Models\ChatUsers;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Создать или получить чат с пользователем.
 */
class ChatCommandCreate
{
    public function handle(array $data): array
    {
        $chats = $this->getChats($data);

        if (count($chats) > 0) {
            return [
                'chat' => $chats,
                'success' => false,
            ];
        }

        $fromUser = User::query()->findOrFail(auth()->user()->id);
        $forUser = User::query()->findOrFail($data['user_id']);

        $chat = Chat::query()->create();

        ChatUsers::query()->create([
            'user_id' => $fromUser->id,
            'chat_id' => $chat->id,
        ]);

        ChatUsers::query()->create([
            'user_id' => $forUser->id,
            'chat_id' => $chat->id,
        ]);

        return [
            'chat' => $chat,
            'success' => true,
        ];
    }

    private function getChats(array $data): Collection
    {
        $chatUsers = ChatUsers::query()
            ->where('user_id', auth()->user()->id)
            ->orWhere('user_id', $data['user_id'])
            ->get();

        return ($chatUsers->groupBy('chat_id'))->filter(function ($items) use ($data) {
            $existFromUser = false;
            $existForUser = false;

            foreach ($items as &$item) {
                if ($item->user_id == auth()->user()->id) {
                    $existFromUser = true;
                }
                if ($item->user_id == $data['user_id']) {
                    $existForUser = true;
                }
            }
            if ($existFromUser & $existForUser) {
                return true;
            }
            return false;
        });
    }
}
