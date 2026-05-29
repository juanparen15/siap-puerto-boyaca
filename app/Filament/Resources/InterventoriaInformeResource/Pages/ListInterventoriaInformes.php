<?php

namespace App\Filament\Resources\InterventoriaInformeResource\Pages;

use App\Filament\Resources\InterventoriaInformeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInterventoriaInformes extends ListRecords
{
    protected static string $resource = InterventoriaInformeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('exportar_pdf')
                ->label('Exportar PDF')
                ->icon('heroicon-o-document-text')
                ->action(function () {
                    $informes = \App\Models\InterventoriaInforme::with('usuario')->orderBy('fecha_informe', 'desc')->get();
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.interventoria-pdf', ['informes' => $informes]);
                    return response()->streamDownload(fn () => print($pdf->output()), 'informes-interventoria.pdf');
                }),
        ];
    }
}
