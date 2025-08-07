<?php

namespace App\Notifications;

use App\Models\Item;

class ItemAtualizadoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_atualizado',
            'message' => 'O item foi atualizado com sucesso.'
        ]);
    }
} 