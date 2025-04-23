<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            $pusher = new \Pusher\Pusher(
                config('broadcasting.connections.pusher.key'),
                config('broadcasting.connections.pusher.secret'),
                config('broadcasting.connections.pusher.app_id'),
                [
                    'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    'useTLS' => true
                ]
            );

            return $pusher->socket_auth($request->channel_name, $request->socket_id);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
} 