<?php

namespace App\Notifications;

use App\Models\Item;

class ItemDevolvidoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_devolvido',
            'message' => 'O item foi marcado como devolvido ao dono.'
        ]);
    }
} 