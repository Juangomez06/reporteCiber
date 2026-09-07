<?php

namespace App\Notifications;

use App\Models\Caso;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CasoAsignadoNotification extends Notification implements ShouldQueue
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
            ->subject("Caso asignado: {$this->caso->codigo}")
            ->line('Se te ha asignado un nuevo caso para orientación.')
            ->action('Ver caso', route('casos.show', $this->caso));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'caso_id' => $this->caso->id,
            'codigo' => $this->caso->codigo,
            'mensaje' => "Se te asignó el caso {$this->caso->codigo}",
        ];
    }
}
