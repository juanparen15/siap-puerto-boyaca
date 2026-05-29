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
}
