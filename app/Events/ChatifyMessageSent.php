<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChMessage;
use App\Models\User;
use App\Notifications\ChatifyMessageNotification;

class ChatifyMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiver;

    public function __construct(ChMessage $message, User $receiver)
    {
        $this->message = $message;
        $this->receiver = $receiver;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->receiver->id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'sender' => $this->message->fromUser
        ];
    }

    public function handle()
    {
        $this->receiver->notify(new ChatifyMessageNotification($this->message));
    }
} 