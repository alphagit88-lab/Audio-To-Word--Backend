<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    // --- GUEST ENDPOINTS ---

    public function startSession(Request $request)
    {
        $guestId = $request->cookie('chat_guest_id') ?? (string) Str::uuid();

        $session = ChatSession::firstOrCreate(
            ['guest_id' => $guestId, 'status' => 'open'],
            [
                'guest_name' => $request->input('name', 'Guest'),
                'guest_email' => $request->input('email'),
                'last_activity_at' => now(),
            ]
        );

        return response()->json(['session_id' => $session->id])->cookie('chat_guest_id', $guestId, 60 * 24 * 365);
    }

    public function guestFetchMessages(Request $request, $sessionId)
    {
        $session = ChatSession::where('id', $sessionId)->where('status', 'open')->firstOrFail();
        $afterId = $request->query('after', 0);

        $messages = $session->messages()->where('id', '>', $afterId)->orderBy('id', 'asc')->get();

        return response()->json(['messages' => $messages, 'status' => $session->status]);
    }

    public function guestSendMessage(Request $request, $sessionId)
    {
        $request->validate(['message' => 'required|string']);
        $session = ChatSession::where('id', $sessionId)->where('status', 'open')->firstOrFail();

        $message = $session->messages()->create([
            'sender_type' => 'guest',
            'message' => $request->message,
        ]);

        $session->update(['last_activity_at' => now()]);

        return response()->json(['message' => $message]);
    }

    // --- ADMIN ENDPOINTS ---

    public function adminView()
    {
        return view('admin.chat');
    }

    public function adminFetchSessions()
    {
        $sessions = ChatSession::orderBy('last_activity_at', 'desc')->get();
        return response()->json(['sessions' => $sessions]);
    }

    public function adminFetchMessages(Request $request, $sessionId)
    {
        $session = ChatSession::findOrFail($sessionId);
        $afterId = $request->query('after', 0);

        $messages = $session->messages()->where('id', '>', $afterId)->orderBy('id', 'asc')->get();

        // Mark guest messages as read
        $session->messages()->where('sender_type', 'guest')->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['messages' => $messages, 'status' => $session->status]);
    }

    public function adminSendMessage(Request $request, $sessionId)
    {
        $request->validate(['message' => 'required|string']);
        $session = ChatSession::findOrFail($sessionId);

        $message = $session->messages()->create([
            'sender_type' => 'admin',
            'admin_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $session->update(['last_activity_at' => now()]);

        return response()->json(['message' => $message]);
    }

    public function adminCloseSession($sessionId)
    {
        $session = ChatSession::findOrFail($sessionId);
        $session->update(['status' => 'closed']);
        return response()->json(['success' => true]);
    }
}
