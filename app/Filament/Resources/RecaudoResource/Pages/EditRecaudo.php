<?php

namespace App\Filament\Resources\RecaudoResource\Pages;

use App\Filament\Resources\RecaudoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRecaudo extends EditRecord
{
    protected static string $resource = RecaudoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
