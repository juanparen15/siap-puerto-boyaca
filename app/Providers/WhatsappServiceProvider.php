<?php
namespace App\Providers;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class WhatsappServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Contracts\WhatsappDriver::class, function ($app) {
            return match(config('services.whatsapp.driver')) {
                'meta' => new \App\Services\Whatsapp\MetaDriver(
                    config('services.whatsapp.meta_token'),
                    config('services.whatsapp.meta_phone_number_id'),
                ),
                default => new \App\Services\Whatsapp\TwilioDriver(
                    new \Twilio\Rest\Client(
                        config('services.twilio.sid'),
                        config('services.twilio.token')
                    ),
                    config('services.twilio.whatsapp_from'),
                ),
            };
        });
    }

    public function boot(): void
    {
        $this->app->make(ChannelManager::class)->extend('whatsapp', function ($app) {
            return new \App\Channels\WhatsappChannel(
                $app->make(\App\Contracts\WhatsappDriver::class)
            );
        });
    }
}
