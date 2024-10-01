<?php

namespace App\Http\Controllers;

use App\Events\NewChatEvent;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations()->latest('updated_at')->get();
        return inertia('Chat/Index',[
            'conversations' => ConversationResource::collection($conversations)->resolve(),

        ]);
    }


    public function show(Conversation $conversation)
    {
        if (! Gate::allows('show',  $conversation)) {
            abort(403);
        }
        Message::where('conversation_id',$conversation->id)
            ->where('receiver_id',auth()->id())
            ->whereNull('read_at')
            ->update(['read_at'=>now()]);

        $messages = $conversation->messages()->get();
        $messages = MessageResource::collection($messages)->resolve();
        $user = $conversation->getReceiver();
        $conversations = auth()->user()->conversations()->latest('updated_at')->get();

        return inertia('Chat/Show',[
            'conversations' => ConversationResource::collection($conversations)->resolve(),
            'conversation_id' => $conversation->id,
            'messages' => $messages,
            'user' => UserResource::make($user)->resolve()

        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->input('user_id');

        $authenticatedUserId = auth()->id();

       # Check if conversation already exists
        $existingConversation = Conversation::where(function ($query) use ($authenticatedUserId, $userId) {
            $query->where('sender_id', $authenticatedUserId)
                ->where('receiver_id', $userId);
        })
            ->orWhere(function ($query) use ($authenticatedUserId, $userId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $authenticatedUserId);
            })->first();

        if ($existingConversation) {
            # Conversation already exists, redirect to existing conversation
            return redirect()->route('chat.show', ['conversation' => $existingConversation->id]);
        }

        # Create new conversation
        $createdConversation = Conversation::create([
            'sender_id' => $authenticatedUserId,
            'receiver_id' => $userId,
        ]);


        broadcast(new NewChatEvent($createdConversation, User::find($userId)))->toOthers();

        return redirect()->route('chat.show', ['conversation' => $createdConversation->id]);
    }

}
