<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturacionPeriodoResource\Pages\CreateFacturacionPeriodo;
use App\Filament\Resources\FacturacionPeriodoResource\Pages\EditFacturacionPeriodo;
use App\Filament\Resources\FacturacionPeriodoResource\Pages\ListFacturacionPeriodos;
use App\Models\FacturacionPeriodo;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FacturacionPeriodoResource extends Resource
{
    protected static ?string $model = FacturacionPeriodo::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Gestión Financiera';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Período de Facturación';

    protected static ?string $pluralModelLabel = 'Facturación';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('empresa_energetica')
                ->required()
                ->maxLength(150),
            TextInput::make('periodo')
                ->required()
                ->placeholder('2026-01')
                ->regex('/^\d{4}-\d{2}$/'),
            TextInput::make('kwh_consumidos')
                ->numeric()
                ->suffix('kWh')
                ->required(),
            TextInput::make('valor_facturado')
                ->numeric()
                ->prefix('$')
                ->required(),
            DatePicker::make('fecha_factura')
                ->required(),
            DatePicker::make('fecha_vencimiento'),
            Select::make('estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'pagada'    => 'Pagada',
                    'vencida'   => 'Vencida',
                ])
                ->default('pendiente')
                ->required(),
            Toggle::make('extraido_por_ia')
                ->label('Extraído con IA')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periodo')->sortable(),
                TextColumn::make('empresa_energetica')->searchable(),
                TextColumn::make('kwh_consumidos')->suffix(' kWh')->numeric(),
                TextColumn::make('valor_facturado')->money('COP'),
                TextColumn::make('fecha_factura')->date('d/m/Y'),
                TextColumn::make('estado')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pagada'  => 'success',
                        'vencida' => 'danger',
                        default   => 'warning',
                    }),
                IconColumn::make('extraido_por_ia')->boolean()->label('IA'),
            ])
            ->defaultSort('periodo', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFacturacionPeriodos::route('/'),
            'create' => CreateFacturacionPeriodo::route('/create'),
            'edit'   => EditFacturacionPeriodo::route('/{record}/edit'),
        ];
    }
}
