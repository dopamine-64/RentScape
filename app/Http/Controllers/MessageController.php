<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Store a new message in a conversation.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $userId = Auth::id();

        // Only participants (owner or tenant) can send messages
        if ($conversation->owner_id !== $userId && $conversation->tenant_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        // Create the message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,  // make sure your column is sender_id
            'message' => $request->message,
        ]);

        // Update conversation updated_at timestamp for ordering
        $conversation->touch();

        // Redirect back to messenger page with the same conversation active
        return redirect()
            ->route('chats.messenger')
            ->with('active_conversation_id', $conversation->id)
            ->with('success', 'Message sent successfully!');
    }
}