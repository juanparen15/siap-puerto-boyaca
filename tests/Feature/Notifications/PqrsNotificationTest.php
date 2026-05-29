<?php

namespace Tests\Feature\Notifications;

use App\Models\Pqrs;
use App\Notifications\PqrsRadicadaNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PqrsNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sends_only_email_when_no_telefono(): void
    {
        Notification::fake();

        $pqrs = Pqrs::factory()->create(['email' => 'test@test.com', 'telefono' => null]);
        $pqrs->notify(new PqrsRadicadaNotification($pqrs));

        Notification::assertSentTo(
            $pqrs,
            PqrsRadicadaNotification::class,
            fn ($notification, $channels) => in_array('mail', $channels) && !in_array('whatsapp', $channels)
        );
    }

    public function test_sends_whatsapp_channel_when_telefono_present(): void
    {
        Notification::fake();

        $pqrs = Pqrs::factory()->create(['email' => 'test@test.com', 'telefono' => '3001234567']);
        $pqrs->notify(new PqrsRadicadaNotification($pqrs));

        Notification::assertSentTo(
            $pqrs,
            PqrsRadicadaNotification::class,
            fn ($notification, $channels) => in_array('mail', $channels) && in_array('whatsapp', $channels)
        );
    }
}
