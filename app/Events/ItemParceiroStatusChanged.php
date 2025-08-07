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
use App\Models\Parceiro;
use App\Models\User;
use App\Notifications\ItemParceiroStatusNotification;

class ItemParceiroStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;
    public $parceiro;
    public $user;
    public $status;

    public function __construct(Item $item, Parceiro $parceiro, User $user, string $status)
    {
        $this->item = $item;
        $this->parceiro = $parceiro;
        $this->user = $user;
        $this->status = $status;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'item' => $this->item,
            'parceiro' => $this->parceiro,
            'status' => $this->status
        ];
    }

    public function handle()
    {
        $this->user->notify(new ItemParceiroStatusNotification($this->item, $this->parceiro, $this->status));
    }
} 