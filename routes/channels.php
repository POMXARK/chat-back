<?php

use App\Models\ChatUsers;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notification.{chatId}', function (User $user, int $chatId) {
//    return ChatUsers::query()->where('chat_id', $chatId)->where('user_id', $user->id)->get();
    return true;
});
