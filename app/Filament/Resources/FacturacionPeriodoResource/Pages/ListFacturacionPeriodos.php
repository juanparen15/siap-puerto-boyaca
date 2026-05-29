<?php

namespace App\Filament\Resources\FacturacionPeriodoResource\Pages;

use App\Filament\Resources\FacturacionPeriodoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFacturacionPeriodos extends ListRecords
{
    protected static string $resource = FacturacionPeriodoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
