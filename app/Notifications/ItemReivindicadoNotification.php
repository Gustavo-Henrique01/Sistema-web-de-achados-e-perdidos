<?php

namespace App\Notifications;

use App\Models\Item;

class ItemReivindicadoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_reivindicado',
            'message' => 'Seu item foi reivindicado por alguém. Por favor, entre em contato para confirmar a devolução.'
        ]);
    }
} 