<?php

namespace App\Filament\Resources\FacturacionPeriodoResource\Pages;

use App\Filament\Resources\FacturacionPeriodoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFacturacionPeriodo extends EditRecord
{
    protected static string $resource = FacturacionPeriodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
