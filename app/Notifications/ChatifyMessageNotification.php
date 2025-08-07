<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\ChMessage;

class ChatifyMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(ChMessage $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'chat_message',
            'message_id' => $this->message->id,
            'from_id' => $this->message->from_id,
            'body' => $this->message->body,
            'created_at' => $this->message->created_at,
            'message' => 'Você recebeu uma nova mensagem.'
        ];
    }
} 