<?php

namespace App\Listeners;

use App\Events\ItemParceiroStatusChanged;
use App\Notifications\ItemParceiroStatusNotification;

class SendItemParceiroStatusNotification
{
    public function handle(ItemParceiroStatusChanged $event)
    {
        $event->user->notify(new ItemParceiroStatusNotification($event->item, $event->parceiro, $event->status));
    }
} 