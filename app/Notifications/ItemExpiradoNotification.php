<?php

namespace App\Notifications;

use App\Models\Item;

class ItemExpiradoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_expirado',
            'message' => 'Um item expirou e foi removido do sistema.',
        ]);
    }
} 