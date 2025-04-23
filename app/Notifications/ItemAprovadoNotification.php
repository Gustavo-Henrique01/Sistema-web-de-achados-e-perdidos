<?php

namespace App\Notifications;

use App\Models\Item;

class ItemAprovadoNotification extends ItemNotification
{
    public function toArray($notifiable)
    {
        return [
            'message' => 'Seu item foi aprovado e está disponível para visualização.',
            'item_name' => $this->item->descricao,
            'item_id' => $this->item->id,
            'item_image' => $this->item->imagem ? asset('storage/' . $this->item->imagem) : null,
            'item_category' => $this->item->categoria->nome
        ];
    }
} 