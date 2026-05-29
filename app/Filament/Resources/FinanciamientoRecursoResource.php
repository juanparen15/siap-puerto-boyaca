<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinanciamientoRecursoResource\Pages\CreateFinanciamientoRecurso;
use App\Filament\Resources\FinanciamientoRecursoResource\Pages\EditFinanciamientoRecurso;
use App\Filament\Resources\FinanciamientoRecursoResource\Pages\ListFinanciamientoRecursos;
use App\Models\FinanciamientoRecurso;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class FinanciamientoRecursoResource extends Resource
{
    protected static ?string $model = FinanciamientoRecurso::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Gestión Financiera';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Financiamiento';

    protected static ?string $pluralModelLabel = 'Financiamientos';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('fuente')
                ->required(),
            TextInput::make('tipo_recurso')
                ->required(),
            TextInput::make('valor')
                ->numeric()
                ->prefix('$'),
            TextInput::make('destinacion')
                ->required(),
            DatePicker::make('fecha_recepcion'),
            Textarea::make('observaciones')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fuente')->searchable(),
                TextColumn::make('tipo_recurso'),
                TextColumn::make('valor')->money('COP'),
                TextColumn::make('destinacion'),
                TextColumn::make('fecha_recepcion')->date('d/m/Y'),
            ])
            ->defaultSort('fecha_recepcion', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFinanciamientoRecursos::route('/'),
            'create' => CreateFinanciamientoRecurso::route('/create'),
            'edit'   => EditFinanciamientoRecurso::route('/{record}/edit'),
        ];
    }
}
