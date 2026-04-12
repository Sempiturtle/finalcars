<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // --- Admin Methods ---

    public function adminIndex()
    {
        // Get all customers for the sidebar
        // We might want to sort them by those who have recent messages
        $customers = User::where('role', 'customer')->get();
        return view('admin.chat.index', compact('customers'));
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

        return response()->json($message);
    }
}
