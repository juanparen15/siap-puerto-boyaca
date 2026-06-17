<?php

namespace App\Filament\Resources\PqrsResource\Pages;

use App\Enums\EstadoPqrs;
use App\Exports\PqrsPeriodoExport;
use App\Filament\Resources\PqrsResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListPqrs extends ListRecords
{
    protected static string $resource = PqrsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('exportar_excel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => Excel::download(new PqrsPeriodoExport(), 'pqrs-' . now()->format('Y-m-d') . '.xlsx')),
        ];
    }

    public function getTabs(): array
    {
        $contar = fn (?EstadoPqrs $estado = null): int => $estado
            ? PqrsResource::getModel()::where('estado', $estado->value)->count()
            : PqrsResource::getModel()::count();

        return [
            'todas' => Tab::make('Todas')->badge($contar()),
            'radicada' => Tab::make('Radicadas')
                ->badge($contar(EstadoPqrs::Radicada))
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', EstadoPqrs::Radicada->value)),
            'en_tramite' => Tab::make('En trámite')
                ->badge($contar(EstadoPqrs::EnTramite))
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', EstadoPqrs::EnTramite->value)),
            'respondida' => Tab::make('Respondidas')
                ->badge($contar(EstadoPqrs::Respondida))
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', EstadoPqrs::Respondida->value)),
            'cerrada' => Tab::make('Cerradas')
                ->badge($contar(EstadoPqrs::Cerrada))
                ->modifyQueryUsing(fn (Builder $q) => $q->where('estado', EstadoPqrs::Cerrada->value)),
        ];
    }
}
