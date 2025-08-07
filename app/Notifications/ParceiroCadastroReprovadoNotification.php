<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Parceiro;

class ParceiroCadastroReprovadoNotification extends Notification implements ShouldQueue
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
            ->subject('Informações sobre o seu cadastro de parceiro')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Agradecemos pelo seu interesse em ser um parceiro do nosso sistema de Achados e Perdidos.')
            ->line('Após análise da nossa equipe, infelizmente seu cadastro não foi aprovado neste momento.')
            ->line('Estabelecimento: ' . $this->parceiro->nome_estabelecimento)
            ->line('Motivo:')
            ->line($this->parceiro->motivo_reprovacao)
            ->line('Você pode atualizar seu cadastro e tentar novamente, corrigindo os pontos mencionados acima.')
            ->action('Fazer Login', url(route('login')))
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
            'motivo' => $this->parceiro->motivo_reprovacao,
            'message' => 'Seu cadastro como parceiro não foi aprovado.'
        ];
    }
}
