<?php

namespace App\Notifications;

use App\Models\Item;

class ItemDevolucaoRecusadaNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_devolucao_recusada',
            'message' => 'A devolução do item foi recusada pelo usuário.',
            'view_url' => route('perfil-usuario')
        ]);
    }
}