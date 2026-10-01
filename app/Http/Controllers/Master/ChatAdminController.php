<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatAdminController extends Controller
{
    public function index()
    {
        // Get all unique session_ids with their latest message
        $sessions = Chat::select('session_id', DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->get();

        return view('master.chat.index', compact('sessions'));
    }

    public function show($sessionId)
    {
        $chats = Chat::where('session_id', $sessionId)->orderBy('created_at', 'asc')->get();
        
        // Mark as read when admin opens it
        Chat::where('session_id', $sessionId)
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($chats);
    }

    public function reply(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'session_id' => $sessionId,
            'is_admin' => true,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($chat);
    }
}
