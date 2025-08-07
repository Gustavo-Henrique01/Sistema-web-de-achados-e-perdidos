<?php

namespace App\Notifications;

use App\Models\Item;

class ItemDevolvidoNotification extends ItemNotification
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
        return array_merge(parent::toArray($notifiable), [
            'type' => 'item_devolvido',
            'message' => 'O item foi marcado como devolvido ao dono.'
        ]);
    }
}