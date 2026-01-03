<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    /**
     * Messenger-style view with role-aware conversations
     */
    public function messenger(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // ✅ Get active role from session (fallback safety)
        $userRole = session('active_role');

        if (!$userRole) {
            // fallback if session somehow missing
            $userRole = $user->role === 'owner' ? 'owner' : 'tenant';
            session(['active_role' => $userRole]);
        }

        // ✅ Fetch conversations based on ACTIVE ROLE
        $conversations = Conversation::with([
                'property',
                'owner',
                'tenant',
                'messages' => function ($q) {
                    $q->orderBy('created_at', 'asc');
                }
            ])
            ->where(function ($q) use ($userId, $userRole) {
                if ($userRole === 'owner') {
                    $q->where('owner_id', $userId);
                } else {
                    $q->where('tenant_id', $userId);
                }
            })
            ->orderByDesc('pinned_by_owner')
            ->orderByDesc('updated_at')
            ->get();

        // ✅ Determine active conversation
        $activeConversationId = $request->query('conversation_id');

        $activeConversation = $activeConversationId
            ? $conversations->firstWhere('id', $activeConversationId)
            : $conversations->first();

        return view('chats.messenger', compact(
            'conversations',
            'activeConversation',
            'userRole'
        ));
    }

    /**
     * Store a new message
     */
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $userId = Auth::id();

        // ✅ Ensure user is part of conversation
        if ($conversation->owner_id !== $userId && $conversation->tenant_id !== $userId) {
            abort(403, 'Unauthorized');
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $request->message,
        ]);

        $conversation->touch();

        return back();
    }

    /**
     * Toggle pin (OWNER MODE ONLY)
     */
    public function togglePin(Conversation $conversation)
    {
        $userRole = session('active_role');

        // ✅ Only allow pinning in OWNER MODE
        if ($userRole !== 'owner' || Auth::id() !== $conversation->owner_id) {
            abort(403);
        }

        $conversation->pinned_by_owner = !$conversation->pinned_by_owner;
        $conversation->save();

        return back();
    }
}