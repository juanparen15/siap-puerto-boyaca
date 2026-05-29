<?php

namespace App\Filament\Resources\FacturacionPeriodoResource\Pages;

use App\Filament\Resources\FacturacionPeriodoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFacturacionPeriodo extends CreateRecord
{
    protected static string $resource = FacturacionPeriodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('extraerConIA')
                ->label('Extraer con IA (Gemini)')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('pdf_factura')
                        ->label('PDF de factura')
                        ->acceptedFileTypes(['application/pdf'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = \Illuminate\Support\Facades\Storage::path($data['pdf_factura']);
                    $base64 = base64_encode(file_get_contents($path));
                    $extracted = app(\App\Services\GeminiService::class)->extraerDatosFactura($base64);

                    if (!empty($extracted)) {
                        $this->form->fill([
                            'empresa_energetica' => $extracted['empresa_energetica'] ?? '',
                            'periodo'            => $extracted['periodo'] ?? '',
                            'kwh_consumidos'     => $extracted['kwh_consumidos'] ?? 0,
                            'valor_facturado'    => $extracted['valor_facturado'] ?? 0,
                            'fecha_factura'      => $extracted['fecha_factura'] ?? null,
                            'fecha_vencimiento'  => $extracted['fecha_vencimiento'] ?? null,
                            'extraido_por_ia'    => true,
                        ]);
                        \Filament\Notifications\Notification::make()
                            ->title('Datos extraídos correctamente')
                            ->success()
                            ->send();
                    } else {
                        \Filament\Notifications\Notification::make()
                            ->title('No se pudo extraer. Complete manualmente.')
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}
