<?php
namespace App\Services\Whatsapp;

use App\Contracts\WhatsappDriver;
use Illuminate\Support\Facades\Http;

class MetaDriver implements WhatsappDriver
{
    public function __construct(private string $token, private string $phoneNumberId) {}

    public function send(string $to, string $message): void
    {
        Http::withToken($this->token)
            ->post("https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'   => $to,
                'type' => 'text',
                'text' => ['body' => $message],
            ]);
    }

    public function sendTemplate(string $to, string $templateName, array $params): void
    {
        Http::withToken($this->token)
            ->post("https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'       => $to,
                'type'     => 'template',
                'template' => [
                    'name'       => $templateName,
                    'language'   => ['code' => 'es'],
                    'components' => [['type' => 'body', 'parameters' => array_map(fn ($v) => ['type' => 'text', 'text' => $v], $params)]],
                ],
            ]);
    }
}
