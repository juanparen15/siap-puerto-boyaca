<?php

namespace App\Filament\Resources\InfraestructuraRedResource\Pages;

use App\Filament\Resources\InfraestructuraRedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInfraestructuraReds extends ListRecords
{
    protected static string $resource = InfraestructuraRedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
