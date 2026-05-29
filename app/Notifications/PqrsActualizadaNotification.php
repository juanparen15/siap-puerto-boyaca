<?php
namespace App\Notifications;

use App\Models\Pqrs;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PqrsActualizadaNotification extends Notification
{
    public function __construct(public readonly Pqrs $pqrs) {}

    public function via(object $notifiable): array
    {
        $channels = ['mail'];
        if ($notifiable->telefono) {
            $channels[] = 'whatsapp';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('PQRS Actualizada — ' . $this->pqrs->radicado)
            ->view('emails.pqrs-actualizada', ['pqrs' => $this->pqrs]);
    }

    public function toWhatsapp(object $notifiable): array
    {
        return [
            'template' => 'pqrs_actualizada',
            'params'   => [$this->pqrs->radicado, $this->pqrs->estado, url('/pqrs/consultar')],
        ];
    }
}
