<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InterventoriaInformeResource\Pages\CreateInterventoriaInforme;
use App\Filament\Resources\InterventoriaInformeResource\Pages\EditInterventoriaInforme;
use App\Filament\Resources\InterventoriaInformeResource\Pages\ListInterventoriaInformes;
use App\Models\InterventoriaInforme;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class InterventoriaInformeResource extends Resource
{
    protected static ?string $model = InterventoriaInforme::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Supervisión';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Informe de Interventoría';

    protected static ?string $pluralModelLabel = 'Informes de Interventoría';

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'interventoria']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tipo_informe')
                ->options([
                    'mensual'     => 'Mensual',
                    'trimestral'  => 'Trimestral',
                    'semestral'   => 'Semestral',
                    'anual'       => 'Anual',
                ])
                ->required(),
            TextInput::make('periodo')
                ->required()
                ->placeholder('2026-01')
                ->regex('/^\d{4}-\d{2}$/'),
            Textarea::make('aspectos_evaluados')
                ->required()
                ->rows(4),
            Textarea::make('cumplimiento_indices')
                ->required()
                ->rows(4),
            TextInput::make('costos_operacion')
                ->numeric()
                ->prefix('$')
                ->nullable(),
            Textarea::make('recomendaciones')
                ->nullable()
                ->rows(3),
            Textarea::make('compromisos_siguiente')
                ->nullable()
                ->rows(3),
            DatePicker::make('fecha_informe')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tipo_informe')->badge(),
                TextColumn::make('periodo')->sortable(),
                TextColumn::make('fecha_informe')->date('d/m/Y'),
                TextColumn::make('usuario.name')->label('Elaborado por'),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->label('Creado'),
            ])
            ->defaultSort('fecha_informe', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInterventoriaInformes::route('/'),
            'create' => CreateInterventoriaInforme::route('/create'),
            'edit'   => EditInterventoriaInforme::route('/{record}/edit'),
        ];
    }
}
