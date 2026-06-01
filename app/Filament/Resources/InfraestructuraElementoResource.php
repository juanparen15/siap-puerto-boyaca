<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfraestructuraElementoResource\Pages\CreateInfraestructuraElemento;
use App\Filament\Resources\InfraestructuraElementoResource\Pages\EditInfraestructuraElemento;
use App\Filament\Resources\InfraestructuraElementoResource\Pages\ListInfraestructuraElementos;
use App\Filament\Resources\InfraestructuraElementoResource\Pages\ViewInfraestructuraElemento;
use App\Models\InfraestructuraElemento;
use BackedEnum;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class InfraestructuraElementoResource extends Resource
{
    protected static ?string $model = InfraestructuraElemento::class;

    // Icon omitted: the 'Alumbrado Público' NavigationGroup already has an icon.
    // Filament v5 forbids items from having icons when their group has one.
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Alumbrado Público';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Elemento';

    protected static ?string $pluralModelLabel = 'Inventario de Elementos';

    protected static ?string $recordTitleAttribute = 'record_title';

    public static function getNavigationBadge(): ?string
    {
        return cache()->remember('nav_badge_elementos', now()->addMinutes(5), fn () =>
            (string) static::getModel()::count()
        );
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identificación')->schema([
                Select::make('tipo')
                    ->options([
                        'luminaria'         => 'Luminaria',
                        'poste'             => 'Poste',
                        'reflector'         => 'Reflector',
                        'sendero_peatonal'  => 'Sendero Peatonal',
                        'campo_deportivo'   => 'Campo Deportivo',
                        'luminaria_parque'  => 'Luminaria de Parque',
                    ])
                    ->required()
                    ->live(),
                TextInput::make('rotulo')->maxLength(50)->label('Rótulo'),
                Select::make('clasificacion')
                    ->options([
                        'casco_urbano'    => 'Casco Urbano',
                        'puerto_serviez'  => 'Puerto Serviez',
                    ])
                    ->required(),
                Select::make('estado')
                    ->options([
                        'operativa'     => 'Operativa',
                        'no_operativa'  => 'No Operativa',
                        'desinstalada'  => 'Desinstalada',
                    ])
                    ->required(),
            ])->columns(2),

            Section::make('Luminaria / Reflector')->schema([
                TextInput::make('marca')->maxLength(100),
                Select::make('tecnologia')
                    ->options([
                        'LED'      => 'LED',
                        'sodio'    => 'Sodio',
                        'mercurio' => 'Mercurio',
                        'otro'     => 'Otro',
                    ]),
                TextInput::make('potencia_w')
                    ->numeric()
                    ->suffix('W')
                    ->label('Potencia (W)')
                    ->minValue(0),
                Select::make('red_id')
                    ->relationship('red', 'nombre')
                    ->label('Circuito / Red')
                    ->searchable()
                    ->preload(),
            ])->columns(2)
              ->visible(fn (Get $get) => in_array($get('tipo'), ['luminaria', 'reflector', 'luminaria_parque'])),

            Section::make('Poste')->schema([
                Select::make('tipo_poste')
                    ->options([
                        'CONCRETO' => 'Concreto',
                        'METALICO' => 'Metálico',
                        'MADERA'   => 'Madera',
                    ])
                    ->label('Tipo de Poste'),
                TextInput::make('altura_poste_m')
                    ->numeric()
                    ->suffix('m')
                    ->label('Altura (m)')
                    ->minValue(0)
                    ->step(0.01),
                TextInput::make('carga_rotura_kgf')
                    ->numeric()
                    ->suffix('kgf')
                    ->label('Carga de Rotura (kgf)')
                    ->minValue(0),
            ])->columns(3)
              ->visible(fn (Get $get) => $get('tipo') === 'poste'),

            Section::make('Área / Sendero')
                ->schema([
                    TextInput::make('descripcion')
                        ->label('Descripción del área')
                        ->maxLength(255),
                    Select::make('red_id')
                        ->relationship('red', 'nombre')
                        ->label('Circuito / Red')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2)
                ->visible(fn (Get $get) => in_array($get('tipo'), ['sendero_peatonal', 'campo_deportivo'])),

            Section::make('Ubicación Georreferenciada')->schema([
                Map::make('location')
                    ->label('Ubicación en el mapa')
                    ->defaultLocation(latitude: 5.977, longitude: -74.579)
                    ->draggable()
                    ->clickable(true)
                    ->zoom(15)
                    ->afterStateUpdated(function (Set $set, ?array $state): void {
                        $set('latitud', $state['lat'] ?? null);
                        $set('longitud', $state['lng'] ?? null);
                    })
                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                        if ($record && $record->latitud !== null && $record->longitud !== null) {
                            $set('location', [
                                'lat' => (float) $record->latitud,
                                'lng' => (float) $record->longitud,
                            ]);
                        }
                    })
                    ->columnSpanFull(),
                TextInput::make('latitud')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->label('Latitud'),
                TextInput::make('longitud')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->label('Longitud'),
                DatePicker::make('fecha_levantamiento')
                    ->label('Fecha de Levantamiento'),
            ])->columns(2),

            Section::make('Descripción')->schema([
                TextInput::make('globalid')
                    ->label('Global ID (Survey123)')
                    ->maxLength(100)
                    ->readOnly(),
                Textarea::make('descripcion')->label('Descripción'),
                Textarea::make('observaciones')->label('Observaciones'),
            ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\InfraestructuraElemento::query()->with('red'))
            ->columns([
            TextColumn::make('rotulo')
                ->searchable()
                ->sortable()
                ->label('Rótulo'),
            TextColumn::make('tipo')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'luminaria'        => 'success',
                    'poste'            => 'primary',
                    'reflector'        => 'warning',
                    'luminaria_parque' => 'info',
                    default            => 'gray',
                }),
            TextColumn::make('estado')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'operativa'    => 'success',
                    'no_operativa' => 'danger',
                    'desinstalada' => 'gray',
                    default        => 'gray',
                }),
            TextColumn::make('marca')->searchable(),
            TextColumn::make('potencia_w')->suffix(' W')->sortable()->label('Potencia'),
            TextColumn::make('clasificacion')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'casco_urbano'   => 'success',
                    'puerto_serviez' => 'primary',
                    default          => 'gray',
                }),
            TextColumn::make('red.nombre')
                ->label('Circuito / Red')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            TextColumn::make('latitud')
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('longitud')
                ->toggleable(isToggledHiddenByDefault: true),
        ])->filters([
            SelectFilter::make('tipo')
                ->options([
                    'luminaria'        => 'Luminaria',
                    'poste'            => 'Poste',
                    'reflector'        => 'Reflector',
                    'sendero_peatonal' => 'Sendero Peatonal',
                    'campo_deportivo'  => 'Campo Deportivo',
                    'luminaria_parque' => 'Luminaria de Parque',
                ]),
            SelectFilter::make('estado')
                ->options([
                    'operativa'    => 'Operativa',
                    'no_operativa' => 'No Operativa',
                    'desinstalada' => 'Desinstalada',
                ]),
            SelectFilter::make('clasificacion')
                ->options([
                    'casco_urbano'   => 'Casco Urbano',
                    'puerto_serviez' => 'Puerto Serviez',
                ]),
        ])->recordActions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ])->defaultSort('rotulo')
          ->defaultPaginationPageOption(25)
          ->paginationPageOptions([25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListInfraestructuraElementos::route('/'),
            'create' => CreateInfraestructuraElemento::route('/create'),
            'view'   => ViewInfraestructuraElemento::route('/{record}'),
            'edit'   => EditInfraestructuraElemento::route('/{record}/edit'),
        ];
    }
}
