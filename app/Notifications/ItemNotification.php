<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Item;

class ItemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'item_id' => $this->item->id,
            'item_name' => $this->item->nome,
            'item_description' => $this->item->descricao,
            'item_location' => $this->item->local,
            'item_date' => $this->item->data,
        ];
    }
} 