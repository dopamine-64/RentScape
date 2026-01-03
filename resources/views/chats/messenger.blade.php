@extends('layouts.app')

@section('content')
<div class="messenger-container">

    {{-- Left: Conversations --}}
    <div class="conversations-col">
        <h4>Chats</h4>
        <div class="conversations-list">
            @foreach($conversations as $conversation)
                @php
                    $userRole = session('active_role');

                    $otherUser = $userRole === 'owner'
                        ? $conversation->tenant
                        : $conversation->owner;

                    $active = ($activeConversation && $activeConversation->id === $conversation->id) ? 'active' : '';
                @endphp

                <div class="conversation-card {{ $active }}">
                    <a href="{{ route('chats.messenger', ['conversation_id' => $conversation->id ?? null]) }}" class="conversation-link">
                        <div>
                            <strong>{{ $otherUser->name }}</strong>
                            <div class="property-title">{{ $conversation->property->title }}</div>
                            @if($conversation->messages->last())
                                <small>{{ Str::limit($conversation->messages->last()->message, 30) }}</small>
                            @endif
                        </div>
                    </a>

                    @if(session('active_role') === 'owner')
                        <form action="{{ route('chats.togglePin', $conversation) }}" method="POST" class="pin-form">
                            @csrf
                            <button type="submit"
                                class="pin-btn {{ $conversation->pinned_by_owner ? 'pinned' : '' }}"
                                title="{{ $conversation->pinned_by_owner ? 'Unpin' : 'Pin' }}">
                                
                                <i class="{{ $conversation->pinned_by_owner ? 'ri-pushpin-fill' : 'ri-pushpin-line' }}"></i>
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Active Conversation --}}
    <div class="chat-col">
        @if($activeConversation)
            @php
                $userRole = session('active_role');

                $chatUser = $userRole === 'owner'
                    ? $activeConversation->tenant
                    : $activeConversation->owner;
            @endphp

            <h4>
                {{ $chatUser->name }}
                <small>{{ $activeConversation->property->title }}</small>
            </h4>

            <div class="chat-window" id="chat-window">
                @foreach($activeConversation->messages->sortBy('created_at') as $message)
                    <div class="chat-message {{ $message->sender_id === Auth::id() ? 'outgoing' : 'incoming' }}">
                        <div class="message-content">
                            <strong>{{ $message->sender->name }}</strong>
                            <p>{{ $message->message }}</p>
                            <small>{{ $message->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('messages.store') }}" method="POST" class="chat-input">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                <input type="text" name="message" placeholder="Type a message..." required autocomplete="off" id="chat-input">
                <button type="submit">Send</button>
            </form>
        @else
            <p>No conversations yet.</p>
        @endif
    </div>

</div>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">

<style>
html, body {
    height: 100%;
    margin: 0;
    overflow: hidden; /* prevent body scrolling */
}

.messenger-container {
    display: flex;
    height: 100vh; /* full screen height */
    gap: 20px;
    padding: 20px;
    box-sizing: border-box;
}

/* Conversations List */
.conversations-col {
    width: 30%;
    display: flex;
    flex-direction: column;
}

.conversations-col h4 {
    margin: 0 0 10px 0;
}

.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding-right: 5px;
}

.conversation-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 14px;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    margin-bottom: 8px;
    transition: background 0.2s;
}

.conversation-card.active {
    background: #f0f0f0;
    border-left: 4px solid #2ecc71;
}

.conversation-card:hover {
    background: #f5f5f5;
}

.conversation-link {
    text-decoration: none;
    color: inherit;
    flex: 1;
}

.property-title {
    font-size: 12px;
    color: #777;
}

.pin-btn {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #999;
    transition: color 0.2s, transform 0.2s;
}

.pin-btn:hover {
    transform: scale(1.1);
}

.pin-btn.pinned {
    color: #f39c12; /* gold when pinned */
}

/* Chat Column */
.chat-col {
    width: 70%;
    display: flex;
    flex-direction: column;
}

.chat-col h4 {
    margin: 0 0 10px 0;
}

.chat-window {
    flex: 1;
    background: #f5f5f5;
    border-radius: 12px;
    padding: 12px;
    max-height: 500px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* Messages */
.chat-message {
    max-width: 70%;
    padding: 10px 14px;
    border-radius: 20px;
    word-wrap: break-word;
    font-size: 14px;
}

.chat-message.incoming {
    background: #3490dc;
    color: #fff;
    align-self: flex-start;
}

.chat-message.outgoing {
    background: #2ecc71;
    color: #fff;
    align-self: flex-end;
}

.chat-message .message-content strong {
    font-size: 11px;
}

.chat-message .message-content p {
    margin: 4px 0 2px 0;
}

.chat-message .message-content small {
    display: block;
    text-align: right;
    font-size: 10px;
    opacity: 0.7;
}

/* Input */
.chat-input {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

.chat-input input[type="text"] {
    flex: 1;
    padding: 10px 16px;
    border-radius: 25px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 14px;
}

.chat-input button {
    padding: 10px 20px;
    border-radius: 25px;
    border: none;
    background: #3490dc;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
}

.chat-input button:hover {
    background: #2779bd;
}
</style>

<script>
    // Scroll chat to bottom on page load
    const chatWindow = document.getElementById('chat-window');
    if(chatWindow){
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    // After submitting the form, scroll to bottom again
    const chatForm = document.querySelector('.chat-input');
    if(chatForm){
        chatForm.addEventListener('submit', function() {
            setTimeout(() => {
                chatWindow.scrollTop = chatWindow.scrollHeight;
            }, 100);
        });
    }
</script>
@endsection