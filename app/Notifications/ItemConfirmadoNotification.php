<?php

namespace App\Notifications;

use App\Models\Item;

class ItemConfirmadoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_confirmado',
            'message' => 'O item foi confirmado pelo parceiro.'
        ]);
    }
} 