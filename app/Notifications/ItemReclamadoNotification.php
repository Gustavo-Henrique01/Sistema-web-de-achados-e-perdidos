<?php

namespace App\Notifications;

use App\Models\Item;

class ItemReclamadoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_reclamado',
            'message' => 'Um item foi reclamado por um usuário.',
        ]);
    }
} 