<?php

namespace App\Notifications;

use App\Models\Caso;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CasoEstadoActualizadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Caso $caso, public string $estadoAnterior) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Actualización del caso {$this->caso->codigo}")
            ->line("El caso cambió de '{$this->estadoAnterior}' a '{$this->caso->estado}'.")
            ->action('Ver caso', route('casos.show', $this->caso));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'caso_id' => $this->caso->id,
            'codigo' => $this->caso->codigo,
            'mensaje' => "El caso {$this->caso->codigo} cambió a estado {$this->caso->estado}",
        ];
    }
}
