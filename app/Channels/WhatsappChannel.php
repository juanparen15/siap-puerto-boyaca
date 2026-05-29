<?php
namespace App\Channels;

use App\Contracts\WhatsappDriver;
use Illuminate\Notifications\Notification;

class WhatsappChannel
{
    public function __construct(private WhatsappDriver $driver) {}

    public function send($notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationForWhatsapp();
        if (!$to) return;

        if (method_exists($notification, 'toWhatsapp')) {
            $data = $notification->toWhatsapp($notifiable);
            $this->driver->sendTemplate($to, $data['template'], $data['params']);
        }
    }
}
