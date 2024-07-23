<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        /** @var Message $this */
        return [
            'messageId' => $this->id,
            'timestamp' => $this->created_at,
            'text' => $this->text,
            'userId' => $this->user_id,
            'user' => [
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
            ]
        ];
    }
}
