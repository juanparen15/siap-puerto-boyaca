<?php

namespace App\Filament\Resources\InfraestructuraElementoResource\Pages;

use App\Filament\Imports\InfraestructuraElementoImporter;
use App\Filament\Resources\InfraestructuraElementoResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

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
        ];
    }
}
