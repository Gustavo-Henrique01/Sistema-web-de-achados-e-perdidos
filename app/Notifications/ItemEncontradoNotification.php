<?php

namespace App\Notifications;

use App\Models\Item;

class ItemEncontradoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_encontrado',
            'message' => 'O item foi marcado como encontrado.'
        ]);
    }
} 