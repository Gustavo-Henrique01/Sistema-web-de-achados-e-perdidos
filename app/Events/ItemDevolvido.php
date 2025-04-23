<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Item;
use App\Models\User;
use App\Notifications\ItemDevolvidoNotification;

class ItemDevolvido implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;
    public $user;

    public function __construct(Item $item, User $user)
    {
        $this->item = $item;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'item' => $this->item
        ];
    }

    public function handle()
    {
        $this->user->notify(new ItemDevolvidoNotification($this->item));
    }
} 