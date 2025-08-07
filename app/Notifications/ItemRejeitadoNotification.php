<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Item;

class ItemRejeitadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;
    protected $motivo;

    public function __construct(Item $item, string $motivo = null)
    {
        $this->item = $item;
        $this->motivo = $motivo;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Seu item foi rejeitado. Motivo: ' . $this->motivo,
            'item_name' => $this->item->descricao,
            'item_id' => $this->item->id,
            'item_image' => $this->item->imagem ? asset('storage/' . $this->item->imagem) : null,
            'item_category' => $this->item->categoria->nome
        ];
    }
} 