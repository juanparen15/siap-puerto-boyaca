<?php

namespace App\Filament\Resources\InfraestructuraRedResource\Pages;

use App\Filament\Resources\InfraestructuraRedResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInfraestructuraRed extends EditRecord
{
    protected static string $resource = InfraestructuraRedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
