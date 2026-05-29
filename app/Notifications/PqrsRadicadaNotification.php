<?php
namespace App\Notifications;

use App\Models\Pqrs;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PqrsRadicadaNotification extends Notification
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
            ->subject('PQRS Radicada — ' . $this->pqrs->radicado)
            ->view('emails.pqrs-radicada', ['pqrs' => $this->pqrs]);
    }

    public function toWhatsapp(object $notifiable): array
    {
        return [
            'template' => 'pqrs_radicada',
            'params'   => [$this->pqrs->radicado, $this->pqrs->tipo_solicitud, url('/pqrs/consultar')],
        ];
    }
}
