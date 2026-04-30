<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // --- Admin Methods ---

    public function adminIndex()
    {
        // Get only users who have a conversation with the current admin
        $customers = User::whereIn('role', ['customer', 'staff'])
            ->where(function($query) {
                $query->whereHas('sentMessages', function($q) {
                    $q->where('receiver_id', Auth::id());
                })
                ->orWhereHas('receivedMessages', function($q) {
                    $q->where('sender_id', Auth::id());
                });
            })
            ->get()
            ->map(function($user) {
                $user->unread_count = ChatMessage::where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id())
                    ->whereNull('read_at')
                    ->count();
                return $user;
            });
        return view('admin.chat.index', compact('customers'));
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->query('q');
        if (!$search) return response()->json([]);

        // Search for both customers and staff members
        $users = User::whereIn('role', ['customer', 'staff'])
            ->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    public function getUnreadCounts()
    {
        return response()->json([
            'total' => Auth::user()->unreadMessagesCount()
        ]);
    }

    public function adminShow(User $user)
    {
        // Mark messages as read
        ChatMessage::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::where(function($query) use ($user) {
                $query->where('sender_id', Auth::id())
                      ->where('receiver_id', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'user' => $user
        ]);
    }

    // --- Customer Methods ---

    public function customerIndex()
    {
        return view('customer.chat.index');
    }

    public function fetchMessages(Request $request)
    {
        $userId = Auth::id();
        $otherUserId = $request->other_user_id; // For admin, this is the selected customer. For customer, this is the admin.

        if (Auth::user()->isCustomer()) {
            // Customers always chat with an admin. Let's find an admin.
            $admin = User::where('role', 'admin')->first();
            $otherUserId = $admin->id;
        }

        $messages = ChatMessage::where(function($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $otherUserId);
            })->orWhere(function($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $otherUserId)
                      ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        ChatMessage::where('sender_id', $otherUserId)
            ->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'sometimes|exists:users,id'
        ]);

        $receiverId = $request->receiver_id;

        if (Auth::user()->isCustomer()) {
            $admin = User::where('role', 'admin')->first();
            $receiverId = $admin->id;
        }

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'message' => $request->message,
        ]);

        // Notify Receiver
        $receiver = User::find($receiverId);
        if ($receiver) {
            $senderName = Auth::user()->name;
            $icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />';
            $url = $receiver->isCustomer() ? route('customer.chat.index') : route('admin.chat.show', ['user' => Auth::id()]);
            
            $receiver->notify(new SystemNotification(
                "New Message from {$senderName}",
                $request->message,
                $icon,
                $url
            ));
        }

        return response()->json($message);
    }
}
