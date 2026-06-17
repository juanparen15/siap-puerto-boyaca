<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    public function __construct(private string $apiKey) {}

    public function extraerDatosFactura(string $pdfBase64): array
    {
        $prompt = 'Extrae del siguiente PDF de factura de energía eléctrica los datos en formato JSON. '
            . 'Devuelve SOLO el JSON sin texto adicional con esta estructura exacta: '
            . '{"empresa_energetica":"","periodo":"YYYY-MM","kwh_consumidos":0.0,'
            . '"valor_facturado":0.0,"fecha_factura":"YYYY-MM-DD","fecha_vencimiento":"YYYY-MM-DD"}';

        $response = Http::post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}",
            ['contents' => [['parts' => [
                ['text' => $prompt],
                ['inline_data' => ['mime_type' => 'application/pdf', 'data' => $pdfBase64]],
            ]]]]
        );

        if ($response->failed()) {
            return [];
        }

        $text = $response->json('candidates.0.content.parts.0.text', '');
        $clean = preg_replace('/```json\n?|```/', '', trim($text));
        return json_decode($clean, true) ?? [];
    }

    public function disponible(): bool
    {
        return filled($this->apiKey);
    }

    /**
     * Mejora la redacción de un borrador de respuesta de PQRS, conservando su sentido.
     */
    public function mejorarRespuesta(string $borrador): string
    {
        if (blank($this->apiKey)) {
            throw new \RuntimeException('No hay API key de Gemini configurada. Configúrala en Configuración del Sistema.');
        }

        $prompt = 'Eres un funcionario de la Alcaldía de Puerto Boyacá que responde una PQRS sobre el '
            . 'servicio de alumbrado público. Mejora la redacción del siguiente borrador para que sea claro, '
            . 'formal, respetuoso y profesional, en español de Colombia. No inventes datos, fechas ni compromisos '
            . 'que no estén en el texto. Devuelve únicamente la respuesta mejorada, sin comillas ni explicaciones.'
            . "\n\nBorrador:\n" . $borrador;

        $response = Http::timeout(25)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}",
            ['contents' => [['parts' => [['text' => $prompt]]]]]
        );

        if ($response->failed()) {
            throw new \RuntimeException('El servicio de IA respondió con error (' . $response->status() . ').');
        }

        $texto = $response->json('candidates.0.content.parts.0.text', '');
        if (blank($texto)) {
            throw new \RuntimeException('La IA no devolvió una respuesta. Intenta de nuevo.');
        }

        return trim(preg_replace('/```/', '', $texto));
    }
}
