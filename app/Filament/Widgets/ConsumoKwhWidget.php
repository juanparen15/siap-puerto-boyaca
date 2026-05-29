<?php
namespace App\Filament\Widgets;

use App\Models\FacturacionPeriodo;
use Filament\Widgets\ChartWidget;

class ConsumoKwhWidget extends ChartWidget
{
    protected ?string $heading = 'Consumo kWh (últimos 6 períodos)';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $datos = FacturacionPeriodo::orderBy('periodo')->take(6)->pluck('kwh_consumidos', 'periodo');
        return [
            'datasets' => [['label' => 'kWh consumidos', 'data' => $datos->values()->toArray(), 'backgroundColor' => '#1B6B2F']],
            'labels'   => $datos->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
