<?php

namespace App\Jobs;

use App\Events\GotMessage;
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
            GotMessage::dispatch([
                'id' => $this->message->id,
                'user_id' => $this->message->user_id,
                'chat_id' => $this->message->chat_id,
                'text' => $this->message->text,
                'time' => $this->message->time,
            ]);
        }
    }
}
