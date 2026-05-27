<?php

namespace App\Filament\Resources\InfraestructuraElementoResource\Pages;

use App\Filament\Resources\InfraestructuraElementoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfraestructuraElementos extends ListRecords
{
    protected static string $resource = InfraestructuraElementoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
