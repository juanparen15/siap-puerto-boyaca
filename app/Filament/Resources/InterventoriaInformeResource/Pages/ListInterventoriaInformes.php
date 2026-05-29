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
        ];
    }
}
