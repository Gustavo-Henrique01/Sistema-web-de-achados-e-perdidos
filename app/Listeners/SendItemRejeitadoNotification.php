<?php

namespace App\Listeners;

use App\Events\ItemRejeitado;
use App\Notifications\ItemRejeitadoNotification;

class SendItemRejeitadoNotification
{
    public function handle(ItemRejeitado $event)
    {
        $event->user->notify(new ItemRejeitadoNotification($event->item, $event->motivo));
    }
} 