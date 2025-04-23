<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Parceiro;

class ParceiroCadastroAprovadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $parceiro;

    /**
     * Create a new notification instance.
     */
    public function __construct(Parceiro $parceiro)
    {
        $this->parceiro = $parceiro;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Seu cadastro de parceiro foi aprovado!')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Temos boas notícias! Seu cadastro como parceiro foi aprovado pela nossa equipe.')
            ->line('Estabelecimento: ' . $this->parceiro->nome_estabelecimento)
            ->line('Agora você pode acessar o sistema e começar a utilizá-lo para gerenciar os itens achados e perdidos.')
            ->action('Acessar o Sistema', url(route('login')))
            ->line('Caso tenha alguma dúvida, entre em contato com nossa equipe de suporte.')
            ->salutation('Atenciosamente, Equipe Achados e Perdidos');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'parceiro_id' => $this->parceiro->id,
            'parceiro_nome' => $this->parceiro->nome_estabelecimento,
            'status' => $this->parceiro->status,
            'message' => 'Seu cadastro como parceiro foi aprovado.'
        ];
    }
}
