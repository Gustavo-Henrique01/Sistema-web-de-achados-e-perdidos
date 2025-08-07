<?php

namespace App\Notifications;

use App\Models\Item;

class ItemPerdidoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_perdido',
            'message' => 'O item foi marcado como perdido.'
        ]);
    }
} 