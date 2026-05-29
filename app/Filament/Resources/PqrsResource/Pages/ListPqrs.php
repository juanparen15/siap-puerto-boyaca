<?php

namespace App\Filament\Resources\PqrsResource\Pages;

use App\Exports\PqrsPeriodoExport;
use App\Filament\Resources\PqrsResource;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPqrs extends ListRecords
{
    protected static string $resource = PqrsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('exportar_excel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(new PqrsPeriodoExport(), 'pqrs-' . now()->format('Y-m-d') . '.xlsx')),
        ];
    }
}
