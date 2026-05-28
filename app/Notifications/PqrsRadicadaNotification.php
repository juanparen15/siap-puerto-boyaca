<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PqrsRadicadaNotification extends Notification
{
    public function __construct(public readonly \App\Models\Pqrs $pqrs) {}

    public function via(object $notifiable): array
    {
        return []; // Channels implemented in Task 13
    }
}
