<?php

namespace App\Listeners;

use App\Events\ChatifyMessageSent;
use App\Notifications\ChatifyMessageNotification;

class SendChatifyMessageNotification
{
    public function handle(ChatifyMessageSent $event)
    {
        $event->receiver->notify(new ChatifyMessageNotification($event->message));
    }
} 