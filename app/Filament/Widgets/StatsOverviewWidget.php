<?php
namespace App\Filament\Widgets;

use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Elementos', InfraestructuraElemento::count())
                ->icon('heroicon-o-light-bulb')->color('success'),
            Stat::make('Operativos', InfraestructuraElemento::where('estado', 'operativa')->count())
                ->color('success'),
            Stat::make('No Operativos', InfraestructuraElemento::where('estado', 'no_operativa')->count())
                ->color('danger'),
            Stat::make('PQRS Pendientes', Pqrs::whereIn('estado', ['radicada', 'en_proceso'])->count())
                ->color('warning'),
        ];
    }
}
