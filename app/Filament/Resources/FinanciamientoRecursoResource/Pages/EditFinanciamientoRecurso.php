<?php

namespace App\Filament\Resources\FinanciamientoRecursoResource\Pages;

use App\Filament\Resources\FinanciamientoRecursoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFinanciamientoRecurso extends EditRecord
{
    protected static string $resource = FinanciamientoRecursoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
