<?php

namespace App\DTO;

class MessageDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $chatId,
        public string $text,
        public string $time,
    ) {
    }


    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'chat_id' => $this->chatId,
            'text' => $this->text,
            'time' => $this->time,
        ];
    }
}
