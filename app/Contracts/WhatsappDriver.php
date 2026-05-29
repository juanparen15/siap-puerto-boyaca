<?php
namespace App\Contracts;

interface WhatsappDriver
{
    public function send(string $to, string $message): void;
    public function sendTemplate(string $to, string $templateName, array $params): void;
}
