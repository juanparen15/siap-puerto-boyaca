<?php

namespace App\Filament\Resources\InterventoriaInformeResource\Pages;

use App\Filament\Resources\InterventoriaInformeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInterventoriaInforme extends EditRecord
{
    protected static string $resource = InterventoriaInformeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
