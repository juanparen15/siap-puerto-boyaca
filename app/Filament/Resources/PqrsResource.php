<?php

namespace App\Filament\Resources;

use App\Enums\EstadoPqrs;
use App\Enums\TipoSolicitud;
use App\Filament\Resources\PqrsResource\Pages\ListPqrs;
use App\Filament\Resources\PqrsResource\Pages\ViewPqrs;
use App\Models\Pqrs;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class PqrsResource extends Resource
{
    protected static ?string $model = Pqrs::class;

    // El grupo 'Alumbrado Público' ya tiene ícono (Filament v5 no permite que el
    // grupo y sus ítems lo tengan a la vez).
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Alumbrado Público';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'PQRS';

    protected static ?string $pluralModelLabel = 'PQRS';

    public static function getNavigationBadge(): ?string
    {
        return cache()->remember('nav_badge_pqrs', now()->addMinutes(2), fn () =>
            (string) (static::getModel()::whereIn('estado', ['radicada', 'en_tramite'])->count() ?: null)
        );
    }

    public static function getNavigationBadgeColor(): string
    {
        return static::getModel()::where('estado', 'radicada')->exists() ? 'danger' : 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del ciudadano')->schema([
                TextEntry::make('radicado'),
                TextEntry::make('tipo_solicitud')
                    ->label('Tipo de solicitud')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => TipoSolicitud::tryFrom($state)?->label() ?? $state)
                    ->color(fn (string $state): string => TipoSolicitud::tryFrom($state)?->color() ?? 'gray'),
                TextEntry::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => EstadoPqrs::tryFrom($state)?->label() ?? $state)
                    ->color(fn (string $state): string => EstadoPqrs::tryFrom($state)?->color() ?? 'gray'),
                TextEntry::make('nombre_ciudadano')->label('Nombre del ciudadano'),
                TextEntry::make('numero_cedula')->label('Número de cédula'),
                TextEntry::make('email')->label('Correo electrónico'),
                TextEntry::make('telefono')->label('Teléfono'),
                TextEntry::make('created_at')->label('Radicada')->dateTime('d/m/Y H:i'),
            ])->columns(2),

            Section::make('Solicitud')->schema([
                TextEntry::make('descripcion')->label('Descripción')->columnSpanFull(),
                TextEntry::make('elemento.rotulo')->label('Elemento'),
                TextEntry::make('accion_tomada')->label('Acción tomada'),
                TextEntry::make('fecha_respuesta')->label('Fecha de respuesta')->dateTime('d/m/Y H:i'),
            ])->columns(2),

            Section::make('Historial de estados')->schema([
                RepeatableEntry::make('historial')
                    ->label('')
                    ->schema([
                        TextEntry::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
                        TextEntry::make('estado_anterior')->label('Estado anterior')
                            ->formatStateUsing(fn (?string $state): string => $state ? (EstadoPqrs::tryFrom($state)?->label() ?? $state) : '—'),
                        TextEntry::make('estado_nuevo')->label('Estado nuevo')
                            ->formatStateUsing(fn (string $state): string => EstadoPqrs::tryFrom($state)?->label() ?? $state),
                        TextEntry::make('observacion')->label('Observación'),
                        TextEntry::make('usuario.name')->label('Registrado por'),
                    ])
                    ->columns(5)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('radicado')->searchable()->sortable(),
                TextColumn::make('tipo_solicitud')->label('Tipo')->badge()
                    ->formatStateUsing(fn (string $state): string => TipoSolicitud::tryFrom($state)?->label() ?? $state)
                    ->color(fn (string $state): string => TipoSolicitud::tryFrom($state)?->color() ?? 'gray'),
                TextColumn::make('estado')->badge()
                    ->formatStateUsing(fn (string $state): string => EstadoPqrs::tryFrom($state)?->label() ?? $state)
                    ->color(fn (string $state): string => EstadoPqrs::tryFrom($state)?->color() ?? 'gray'),
                TextColumn::make('nombre_ciudadano')->searchable(),
                TextColumn::make('elemento.rotulo')->label('Elemento')->searchable(),
                TextColumn::make('funcionario.name')->label('Asignado a')->placeholder('Sin asignar'),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->label('Radicada'),
            ])
            ->filters([
                SelectFilter::make('estado')->options(EstadoPqrs::opciones()),
                SelectFilter::make('tipo_solicitud')->label('Tipo')->options(TipoSolicitud::opciones()),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('cambiarEstado')
                    ->label('Cambiar estado')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Select::make('estado_nuevo')
                            ->label('Nuevo estado')
                            ->options([
                                EstadoPqrs::EnTramite->value  => EstadoPqrs::EnTramite->label(),
                                EstadoPqrs::Respondida->value => EstadoPqrs::Respondida->label(),
                                EstadoPqrs::Cerrada->value    => EstadoPqrs::Cerrada->label(),
                            ])
                            ->required(),
                        Textarea::make('observacion')->label('Observación interna'),
                        Textarea::make('accion_tomada')->label('Acción tomada / respuesta al ciudadano'),
                    ])
                    ->action(function (Pqrs $record, array $data): void {
                        $record->cambiarEstado(
                            $data['estado_nuevo'],
                            $data['observacion'] ?? null,
                            auth()->id(),
                            $data['accion_tomada'] ?? null,
                        );
                        $record->notify(new \App\Notifications\PqrsActualizadaNotification($record));
                        cache()->forget('nav_badge_pqrs');
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPqrs::route('/'),
            'view'  => ViewPqrs::route('/{record}'),
        ];
    }
}
