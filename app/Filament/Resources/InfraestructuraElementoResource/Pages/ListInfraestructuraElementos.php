<?php

namespace App\Filament\Resources\InfraestructuraElementoResource\Pages;

use App\Exports\InventarioExport;
use App\Filament\Imports\InfraestructuraElementoImporter;
use App\Filament\Resources\InfraestructuraElementoResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListInfraestructuraElementos extends ListRecords
{
    protected static string $resource = InfraestructuraElementoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(InfraestructuraElementoImporter::class)
                ->label('Importar CSV Survey123')
                ->icon('heroicon-o-arrow-up-tray'),
            CreateAction::make(),
            \Filament\Actions\Action::make('exportar_excel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(new InventarioExport(), 'inventario-' . now()->format('Y-m-d') . '.xlsx')),
        ];
    }
}
