<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfraestructuraRedResource\Pages\CreateInfraestructuraRed;
use App\Filament\Resources\InfraestructuraRedResource\Pages\EditInfraestructuraRed;
use App\Filament\Resources\InfraestructuraRedResource\Pages\ListInfraestructuraReds;
use App\Models\InfraestructuraRed;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class InfraestructuraRedResource extends Resource
{
    protected static ?string $model = InfraestructuraRed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSignal;

    protected static string|UnitEnum|null $navigationGroup = 'Alumbrado Público';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Red / Circuito';

    protected static ?string $pluralModelLabel = 'Redes y Circuitos';

    protected static ?string $recordTitleAttribute = 'nombre';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Información General')->schema([
                Select::make('tipo')
                    ->options([
                        'alimentacion' => 'Alimentación',
                        'canalizacion' => 'Canalización',
                        'transformador' => 'Transformador',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('nombre')->required()->maxLength(150),
                Select::make('uso')
                    ->options(['exclusivo' => 'Uso Exclusivo', 'compartido' => 'Compartido'])
                    ->required(),
                Select::make('clasificacion')
                    ->options(['casco_urbano' => 'Casco Urbano', 'puerto_serviez' => 'Puerto Serviez'])
                    ->required(),
            ])->columns(2),
            Section::make('Detalles Técnicos')->schema([
                TextInput::make('material')
                    ->maxLength(100)
                    ->visible(fn (Get $get) => in_array($get('tipo'), ['alimentacion', 'canalizacion'])),
                TextInput::make('calibre_conductores')
                    ->maxLength(50)
                    ->visible(fn (Get $get) => $get('tipo') === 'alimentacion'),
                Select::make('tipo_instalacion')
                    ->options(['aerea' => 'Aérea', 'subterranea' => 'Subterránea'])
                    ->visible(fn (Get $get) => $get('tipo') === 'alimentacion'),
                Select::make('tipo_zona')
                    ->options(['dura' => 'Zona Dura', 'verde' => 'Zona Verde', 'cruce_calzada' => 'Cruce de Calzada'])
                    ->visible(fn (Get $get) => $get('tipo') === 'canalizacion'),
                Select::make('tipo_transformador')
                    ->options(['aereo' => 'Aéreo', 'local' => 'Local', 'pedestal' => 'Pedestal', 'subterraneo' => 'Subterráneo'])
                    ->visible(fn (Get $get) => $get('tipo') === 'transformador'),
                TextInput::make('potencia_kva')
                    ->numeric()
                    ->label('Potencia (kVA)')
                    ->visible(fn (Get $get) => $get('tipo') === 'transformador'),
                TextInput::make('tension_primaria_kv')
                    ->numeric()
                    ->label('Tensión Primaria (kV)')
                    ->visible(fn (Get $get) => $get('tipo') === 'transformador'),
                TextInput::make('tension_secundaria_kv')
                    ->numeric()
                    ->label('Tensión Secundaria (kV)')
                    ->visible(fn (Get $get) => $get('tipo') === 'transformador'),
            ])->columns(2),
            Textarea::make('observaciones')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nombre')->searchable()->sortable(),
            TextColumn::make('tipo')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'alimentacion' => 'primary',
                    'transformador' => 'success',
                    'canalizacion' => 'warning',
                    default => 'gray',
                }),
            TextColumn::make('uso')->badge(),
            TextColumn::make('clasificacion')->badge(),
            TextColumn::make('elementos_count')
                ->counts('elementos')
                ->label('Elementos'),
        ])->filters([
            SelectFilter::make('tipo')
                ->options([
                    'alimentacion' => 'Alimentación',
                    'canalizacion' => 'Canalización',
                    'transformador' => 'Transformador',
                ]),
            SelectFilter::make('clasificacion')
                ->options([
                    'casco_urbano' => 'Casco Urbano',
                    'puerto_serviez' => 'Puerto Serviez',
                ]),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInfraestructuraReds::route('/'),
            'create' => CreateInfraestructuraRed::route('/create'),
            'edit'   => EditInfraestructuraRed::route('/{record}/edit'),
        ];
    }
}
