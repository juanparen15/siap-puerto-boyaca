<?php
namespace App\Services\Whatsapp;

use App\Contracts\WhatsappDriver;
use Twilio\Rest\Client;

class TwilioDriver implements WhatsappDriver
{
    public function __construct(private Client $client, private string $from) {}

    public function send(string $to, string $message): void
    {
        $this->client->messages->create("whatsapp:+{$to}", [
            'from' => "whatsapp:{$this->from}",
            'body' => $message,
        ]);
    }

    public function sendTemplate(string $to, string $templateName, array $params): void
    {
        $this->client->messages->create("whatsapp:+{$to}", [
            'from'             => "whatsapp:{$this->from}",
            'contentSid'       => $templateName,
            'contentVariables' => json_encode($params),
        ]);
    }
}
