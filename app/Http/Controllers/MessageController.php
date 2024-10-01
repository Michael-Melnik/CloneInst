<?php

namespace App\Http\Controllers;

use App\Events\ChatUpdateEvent;
use App\Events\MessageSent;
use App\Events\NewChatEvent;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(int $conversation, StoreMessageRequest $request)
    {

        $data = $request->validated();

        $conversation = Conversation::find($conversation);

        $message = $conversation->messages()->create($data);

        broadcast(new MessageSent($message))->toOthers();
        broadcast(new ChatUpdateEvent($conversation))->toOthers();

        return $message;
    }
}
