<?php

namespace App\Filament\Resources\FinanciamientoRecursoResource\Pages;

use App\Filament\Resources\FinanciamientoRecursoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFinanciamientoRecursos extends ListRecords
{
    protected static string $resource = FinanciamientoRecursoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
