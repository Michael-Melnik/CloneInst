<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => UserResource::make($this->getReceiver())->resolve(),
            'messages' => $this->messages->count(),
            'unread_message_count' => $this->unreadMessagesCount(),
            'last_message' => !empty($this->messages->last()) ? MessageResource::make($this->messages->last())->resolve() : null,

        ];
    }
}
