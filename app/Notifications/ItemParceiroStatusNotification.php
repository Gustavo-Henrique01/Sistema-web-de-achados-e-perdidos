<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Item;
use App\Models\Parceiro;

class ItemParceiroStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $item;
    protected $parceiro;
    protected $status;

    public function __construct(Item $item, Parceiro $parceiro, string $status)
    {
        $this->item = $item;
        $this->parceiro = $parceiro;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = $this->status === 'aceito' 
            ? "O parceiro {$this->parceiro->nome_estabelecimento} aceitou o item {$this->item->nome}."
            : "O parceiro {$this->parceiro->nome_estabelecimento} rejeitou o item {$this->item->nome}.";

        return [
            'type' => 'item_parceiro_status',
            'item_id' => $this->item->id,
            'item_name' => $this->item->descricao,
            'parceiro_id' => $this->parceiro->id,
            'parceiro_name' => $this->parceiro->nome_estabelecimento,
            'status' => $this->status,
            'message' => $message,
            'item_image' => $this->item->imagem ? asset('storage/' . $this->item->imagem) : null,
            'item_category' => $this->item->categoria->nome
        ];
    }
} 