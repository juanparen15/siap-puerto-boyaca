<?php

namespace App\Filament\Resources\FacturacionPeriodoResource\Pages;

use App\Exports\FacturacionAnualExport;
use App\Filament\Resources\FacturacionPeriodoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListFacturacionPeriodos extends ListRecords
{
    protected static string $resource = FacturacionPeriodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('exportar_excel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(new FacturacionAnualExport(), 'facturacion-' . now()->format('Y-m-d') . '.xlsx')),
        ];
    }
}
