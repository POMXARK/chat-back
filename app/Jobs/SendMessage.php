<?php

namespace App\Jobs;

use App\DTO\MessageDTO;
use App\Events\MessageEvent;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Message $message)
    {
        //
    }

    public function handle(): void
    {
        if ($this->message->chat_id) {
            /** @var SendMessage $this */
            MessageEvent::dispatch((new MessageDTO(
                id: $this->message->id,
                userId: $this->message->user_id,
                chatId: $this->message->chat_id,
                text: $this->message->text,
                time: $this->message->time,
            ))->toArray());
        }
    }
}
