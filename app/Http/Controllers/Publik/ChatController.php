<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        return view('publik.chat.index');
    }

    public function getMessages(Request $request)
    {
        $sessionId = $request->session()->getId();
        $chats = Chat::where('session_id', $sessionId)->orderBy('created_at', 'asc')->get();
        return response()->json($chats);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $sessionId = $request->session()->getId();
        
        $chat = Chat::create([
            'session_id' => $sessionId,
            'is_admin' => false,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($chat);
    }
}
