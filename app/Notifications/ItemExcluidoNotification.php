<?php

namespace App\Notifications;

use App\Models\Item;

class ItemExcluidoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_excluido',
            'message' => 'O item foi excluído do sistema.'
        ]);
    }
} 