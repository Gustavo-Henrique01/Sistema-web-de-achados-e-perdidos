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
use App\Notifications\ItemRejeitadoNotification;

class ItemRejeitado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $item;
    public $user;
    public $motivo;

    public function __construct(Item $item, User $user, string $motivo = null)
    {
        $this->item = $item;
        $this->user = $user;
        $this->motivo = $motivo;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastWith()
    {
        return [
            'item' => $this->item,
            'motivo' => $this->motivo
        ];
    }

    public function handle()
    {
        $this->user->notify(new ItemRejeitadoNotification($this->item, $this->motivo));
    }
} 