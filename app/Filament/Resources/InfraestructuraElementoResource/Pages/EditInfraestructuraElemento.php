<?php

namespace App\Filament\Resources\InfraestructuraElementoResource\Pages;

use App\Filament\Resources\InfraestructuraElementoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInfraestructuraElemento extends EditRecord
{
    protected static string $resource = InfraestructuraElementoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
