<?php

namespace App\Notifications;

use App\Enums\EstadoPqrs;
use App\Models\Pqrs;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PqrsEstadoNotification extends Notification
{
    public function __construct(
        public readonly Pqrs $pqrs,
        public readonly EstadoPqrs $estado,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->routeNotificationForMail()) {
            $channels[] = 'mail';
        }
        if ($notifiable->routeNotificationForWhatsapp()) {
            $channels[] = 'whatsapp';
        }
        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $asunto = match ($this->estado->value) {
            'radicada'   => 'PQRS radicada',
            'en_tramite' => 'PQRS en trámite',
            'respondida' => 'PQRS respondida',
            'cerrada'    => 'PQRS cerrada',
            default      => 'Actualización de PQRS',
        };

        return (new MailMessage)
            ->subject($asunto . ' — ' . $this->pqrs->radicado)
            ->view('emails.pqrs-estado', [
                'pqrs'   => $this->pqrs,
                'estado' => $this->estado,
            ]);
    }

    public function toWhatsapp(object $notifiable): array
    {
        return [
            'template' => 'pqrs_' . $this->estado->value,
            'params'   => [
                $this->pqrs->radicado,
                $this->estado->label(),
                url('/pqrs/consultar'),
            ],
        ];
    }
}
