<?php

namespace App\Http\Controllers;

use App\Events\ChatifyMessageSent;
use App\Models\ChMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatifyController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = ChMessage::create([
            'from_id' => auth()->id(),
            'to_id' => $request->to_id,
            'body' => $request->body,
        ]);
        
        event(new ChatifyMessageSent($message, User::find($request->to_id)));
    }
} 