<?php

namespace App\Notifications;

use App\Models\Caso;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CasoCreadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Caso $caso) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nuevo caso reportado: {$this->caso->codigo}")
            ->line("Se ha registrado un nuevo caso de tipo {$this->caso->tipo_acoso}.")
            ->action('Ver caso', route('casos.show', $this->caso))
            ->line('Por favor revísalo y asígnalo a un orientador.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'caso_id' => $this->caso->id,
            'codigo' => $this->caso->codigo,
            'mensaje' => "Nuevo caso reportado: {$this->caso->codigo}",
        ];
    }
}
