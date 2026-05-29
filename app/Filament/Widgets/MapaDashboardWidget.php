<?php
namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MapaDashboardWidget extends Widget
{
    protected static ?int $sort = 2;
    protected static string $view = 'filament.widgets.mapa-dashboard';
    protected int | string | array $columnSpan = 'full';
}
