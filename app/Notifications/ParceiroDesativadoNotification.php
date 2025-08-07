<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Parceiro;

class ParceiroDesativadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $parceiro;
    protected $motivo;

    public function __construct(Parceiro $parceiro, string $motivo)
    {
        $this->parceiro = $parceiro;
        $this->motivo = $motivo;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Seu acesso ao sistema foi temporariamente suspenso')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Seu acesso ao sistema de Achados e Perdidos foi temporariamente suspenso.')
            ->line('Estabelecimento: ' . $this->parceiro->nome_estabelecimento)
            ->line('Motivo: ' . $this->motivo)
            ->line('Seu acesso ao sistema foi temporariamente suspenso, caso tenha dúvidas ou gostaria de contestar retorne uma mensagem para nossa equipe de suporte nesse email.')
            ->salutation('Atenciosamente, ache aqui, Equipe de Suporte');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'parceiro_id' => $this->parceiro->id,
            'parceiro_nome' => $this->parceiro->nome_estabelecimento,
            'motivo' => $this->motivo,
            'message' => 'Seu acesso ao sistema foi temporariamente suspenso, caso tenha dúvidas ou gostaria de contestar retorne uma mensagem para nossa equipe de suporte nesse email acheaqui.cg.ms@gmail.com .',
        ];
    }
}