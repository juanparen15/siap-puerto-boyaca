<?php

namespace App\Filament\Resources\RecaudoResource\Pages;

use App\Filament\Resources\RecaudoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRecaudos extends ListRecords
{
    protected static string $resource = RecaudoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
