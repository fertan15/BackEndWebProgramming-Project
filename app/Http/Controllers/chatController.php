<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chats;
use App\Models\Messages;
use App\Models\Users;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class chatController extends Controller
{
    
    public function chat(){
        $currentUserId = Auth::id();
        
        // Get all chats where the current user is involved
        $chats = Chats::where('user1_id', $currentUserId)
                       ->orWhere('user2_id', $currentUserId)
                       ->get();
        
        $chatsList = [];
        
        foreach($chats as $chat) {
            // Determine who is the other person in the conversation
            $otherPersonId = ($chat->user1_id === $currentUserId) ? $chat->user2_id : $chat->user1_id;
            $otherPerson = Users::find($otherPersonId);
            
            if(!$otherPerson) {
                continue; // Skip if user not found
            }
            
            // Get the latest message in this chat
            $lastMessage = Messages::where('chat_id', $chat->id)
                                   ->orderBy('sent_at', 'desc')
                                   ->first();
            
            // Count unread messages for this chat (messages sent by other person that are not read)
            $unreadCount = Messages::where('chat_id', $chat->id)
                                   ->where('sender_id', '!=', $currentUserId)
                                   ->where('read', 0)  // Only count unread messages
                                   ->count();
            
            $chatsList[] = [
                'name' => $otherPerson->name ?? 'Unknown User',
                'avatar' => $otherPerson->identity_image_url ?? 'https://i.pravatar.cc/150?img=1',
                'is_online' => true,
                'unread_count' => $unreadCount,
                'time' => $lastMessage ? $lastMessage->sent_at->format('g:i A') : 'No messages',
                'last_message' => $lastMessage ? $lastMessage->content : 'No messages yet',
                'chat_id' => $chat->id,
                'user_id' => $otherPersonId,
            ];
        }
        
        $users = Users::where('id', '!=', $currentUserId)
                      ->select('id', 'name', 'username', 'identity_image_url')
                      ->get()
                      ->map(function ($u) {
                          return [
                              'id' => $u->id,
                              'name' => $u->name,
                              'username' => $u->username,
                              'avatar' => $u->identity_image_url ?? 'https://i.pravatar.cc/150?img=1',
                          ];
                      });
        
        return view('chatMenu', [
            'chats' => $chatsList,
            'users' => $users,
            'currentUserId' => $currentUserId,
        ]);
    }

    public function start(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $currentUserId = Auth::id();
        $targetUserId = (int) $request->input('user_id');

        if ($currentUserId === $targetUserId) {
            return response()->json(['success' => false, 'message' => 'You cannot start a chat with yourself.'], 422);
        }

        // Find or create chat between the two users
        $chat = Chats::where(function ($q) use ($currentUserId, $targetUserId) {
                        $q->where('user1_id', $currentUserId)->where('user2_id', $targetUserId);
                    })
                    ->orWhere(function ($q) use ($currentUserId, $targetUserId) {
                        $q->where('user1_id', $targetUserId)->where('user2_id', $currentUserId);
                    })
                    ->first();

        if (!$chat) {
            $chat = Chats::create([
                'user1_id' => $currentUserId,
                'user2_id' => $targetUserId,
                'created_at' => Carbon::now(),
            ]);
        }

        $messageText = trim((string) $request->input('message', ''));
        if ($messageText !== '') {
            Messages::create([
                'chat_id' => $chat->id,
                'sender_id' => $currentUserId,
                'content' => $messageText,
                'sent_at' => Carbon::now(),
                'read' => 0,
            ]);
        }

        $otherUser = Users::find($targetUserId);
        $lastMessage = Messages::where('chat_id', $chat->id)
                               ->orderBy('sent_at', 'desc')
                               ->first();

        return response()->json([
            'success' => true,
            'chat' => [
                'chat_id' => $chat->id,
                'user_id' => $otherUser->id,
                'name' => $otherUser->name ?? 'Unknown User',
                'avatar' => $otherUser->identity_image_url ?? 'https://i.pravatar.cc/150?img=1',
                'is_online' => true,
                'unread_count' => 0,
                'time' => $lastMessage ? $lastMessage->sent_at->format('g:i A') : Carbon::now()->format('g:i A'),
                'last_message' => $lastMessage ? $lastMessage->content : ($messageText ?: 'No messages yet'),
            ],
        ]);
    }

    public function messages(Chats $chat)
    {
        $currentUserId = Auth::id();
        if (!$this->userInChat($chat, $currentUserId)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Mark all messages from the other user as read when opening the chat
        Messages::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $currentUserId)
            ->where('read', 0)
            ->update(['read' => 1]);

        $messages = Messages::where('chat_id', $chat->id)
                            ->orderBy('sent_at', 'asc')
                            ->get()
                            ->map(function ($m) {
                                return [
                                    'id' => $m->id,
                                    'sender_id' => $m->sender_id,
                                    'content' => $m->content,
                                    'sent_at' => $m->sent_at ? $m->sent_at->format('Y-m-d H:i:s') : null,
                                ];
                            });

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function sendMessage(Request $request, Chats $chat)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $currentUserId = Auth::id();
        if (!$this->userInChat($chat, $currentUserId)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $message = Messages::create([
            'chat_id' => $chat->id,
            'sender_id' => $currentUserId,
            'content' => $request->input('content'),
            'sent_at' => Carbon::now(),
            'read' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'content' => $message->content,
                'sent_at' => $message->sent_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    private function userInChat(Chats $chat, $userId): bool
    {
        return $chat->user1_id === $userId || $chat->user2_id === $userId;
    }
}
