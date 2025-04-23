<?php

namespace App\Listeners;

use App\Events\ItemDevolvido;
use App\Notifications\ItemDevolvidoNotification;

class SendItemDevolvidoNotification
{
    public function handle(ItemDevolvido $event)
    {
        $event->user->notify(new ItemDevolvidoNotification($event->item));
    }
} 