<?php

namespace App\Filament\Resources\InfraestructuraElementoResource\Pages;

use App\Filament\Resources\InfraestructuraElementoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInfraestructuraElemento extends ViewRecord
{
    protected static string $resource = InfraestructuraElementoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
