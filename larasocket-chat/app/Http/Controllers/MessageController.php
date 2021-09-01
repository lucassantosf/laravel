<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return Message::with('user')->get();
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        // send event to listeners
        broadcast(new MessageSentEvent($message, $user))->toOthers();

        $header = [];

        return [
            'message' => $message,
            'user' => $user,
            'header' => $header
        ];
    }
}