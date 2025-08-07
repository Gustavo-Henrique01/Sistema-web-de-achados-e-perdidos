<?php

namespace App\Notifications;

use App\Models\Item;

class ItemConfirmacaoDevolucaoNotification extends ItemNotification
{
    /**
     * Define os canais pelos quais a notificação será enviada.
     * Garante que a notificação seja enviada apenas pelo canal database (área de notificações).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Apenas área de notificações, sem email
    }

    public function toArray($notifiable)
    {
        // Carrega relacionamentos necessários para exibir mais informações
        $this->item->load(['categoria', 'fotos']);
        
        // Obtém a imagem do item, se disponível
        $itemImage = null;
        if ($this->item->fotos && $this->item->fotos->count() > 0) {
            $itemImage = asset('storage/' . $this->item->fotos->first()->caminho);
        }
        
        // Obtém o nome da categoria, se disponível
        $itemCategory = $this->item->categoria ? $this->item->categoria->nome_categoria : null;
        
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_confirmacao_devolucao',
            'message' => 'Você devolveu este item? Por favor, confirme ou recuse a devolução.',
            'item_name' => $this->item->descricao,
            'item_category' => $itemCategory,
            'item_image' => $itemImage,
            'view_url' => route('item.exibir-confirmacao-devolucao', ['item' => $this->item->id]),
            'action_text' => 'Confirmar Devolução',
            'action_url' => route('item.confirmar-devolucao-get', ['item' => $this->item->id]),
            'reject_text' => 'Recusar Devolução',
            'reject_url' => route('item.recusar-devolucao-get', ['item' => $this->item->id]),
            'requires_confirmation' => true,
        ]);
    }
}
