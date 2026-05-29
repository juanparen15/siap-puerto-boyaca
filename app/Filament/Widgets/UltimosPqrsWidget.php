<?php
namespace App\Filament\Widgets;

use App\Models\Pqrs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosPqrsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Pqrs::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('radicado'),
                Tables\Columns\TextColumn::make('tipo_solicitud')->badge(),
                Tables\Columns\TextColumn::make('estado')->badge()
                    ->color(fn (string $state) => match($state) {
                        'radicada' => 'danger', 'en_proceso' => 'warning',
                        'resuelta' => 'success', default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Fecha'),
            ])
            ->paginated(false);
    }
}
