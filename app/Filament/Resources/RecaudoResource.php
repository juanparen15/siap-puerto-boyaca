<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecaudoResource\Pages\CreateRecaudo;
use App\Filament\Resources\RecaudoResource\Pages\EditRecaudo;
use App\Filament\Resources\RecaudoResource\Pages\ListRecaudos;
use App\Models\Recaudo;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class RecaudoResource extends Resource
{
    protected static ?string $model = Recaudo::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Gestión Financiera';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Recaudo';

    protected static ?string $pluralModelLabel = 'Recaudos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('periodo')
                ->required()
                ->placeholder('2026-01')
                ->regex('/^\d{4}-\d{2}$/'),
            TextInput::make('concepto')
                ->required(),
            TextInput::make('valor_recaudado')
                ->numeric()
                ->prefix('$')
                ->required(),
            TextInput::make('fuente_pago')
                ->required(),
            DatePicker::make('fecha_recaudo')
                ->required(),
            Textarea::make('observaciones')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periodo')->sortable(),
                TextColumn::make('concepto')->searchable(),
                TextColumn::make('valor_recaudado')->money('COP'),
                TextColumn::make('fuente_pago'),
                TextColumn::make('fecha_recaudo')->date('d/m/Y'),
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
            'index'  => ListRecaudos::route('/'),
            'create' => CreateRecaudo::route('/create'),
            'edit'   => EditRecaudo::route('/{record}/edit'),
        ];
    }
}
