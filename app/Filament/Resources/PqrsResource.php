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
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
            Section::make('Resumen')
                ->icon('heroicon-o-clipboard-document-check')
                ->columns(3)
                ->schema([
                    TextEntry::make('radicado')->label('Radicado')->copyable(),
                    TextEntry::make('tipo_solicitud')->label('Tipo')->badge()
                        ->formatStateUsing(fn (string $state): string => TipoSolicitud::tryFrom($state)?->label() ?? $state)
                        ->color(fn (string $state): string => TipoSolicitud::tryFrom($state)?->color() ?? 'gray'),
                    TextEntry::make('estado')->label('Estado')->badge()
                        ->formatStateUsing(fn (string $state): string => EstadoPqrs::tryFrom($state)?->label() ?? $state)
                        ->color(fn (string $state): string => EstadoPqrs::tryFrom($state)?->color() ?? 'gray'),
                    TextEntry::make('created_at')->label('Radicada')->dateTime('d/m/Y H:i'),
                    TextEntry::make('fecha_limite')->label('Fecha límite')
                        ->getStateUsing(fn (Pqrs $record): string => $record->fecha_limite?->format('d/m/Y') ?? 'Sin plazo'),
                    TextEntry::make('vencimiento')->label('Vencimiento')->badge()
                        ->getStateUsing(function (Pqrs $record): string {
                            $s = $record->semaforo;
                            if ($s === null) return 'Sin plazo';
                            if ($s === 'cumplida') return 'Atendida';
                            $d = (int) $record->dias_restantes;
                            return $d < 0 ? 'Vencida ' . abs($d) . 'd' : $d . ' día(s) háb.';
                        })
                        ->color(fn (Pqrs $record): string => match ($record->semaforo) {
                            'verde' => 'success', 'ambar' => 'warning', 'rojo' => 'danger', default => 'gray',
                        }),
                    TextEntry::make('funcionario.name')->label('Asignado a')->placeholder('Sin asignar')->icon('heroicon-m-user'),
                ]),

            Section::make('Datos del ciudadano')
                ->icon('heroicon-o-user-circle')
                ->columns(2)
                ->schema([
                    TextEntry::make('nombre_ciudadano')->label('Nombre'),
                    TextEntry::make('numero_cedula')->label('Cédula'),
                    TextEntry::make('email')->label('Correo electrónico')->placeholder('—')->copyable(),
                    TextEntry::make('telefono')->label('Teléfono')->placeholder('—')->copyable(),
                ]),

            Section::make('Solicitud')
                ->icon('heroicon-o-document-text')
                ->columns(2)
                ->schema([
                    TextEntry::make('descripcion')->label('Descripción')->columnSpanFull(),
                    TextEntry::make('elemento.rotulo')->label('Elemento reportado')->placeholder('—'),
                    TextEntry::make('accion_tomada')->label('Respuesta / acción tomada')->placeholder('Pendiente')->columnSpanFull(),
                    TextEntry::make('fecha_respuesta')->label('Fecha de respuesta')->dateTime('d/m/Y H:i')->placeholder('—'),
                ]),

            Section::make('Ubicación del reporte')
                ->icon('heroicon-o-map-pin')
                ->schema([
                    ViewEntry::make('ubicacion')->hiddenLabel()->view('filament.infolists.pqrs-mapa'),
                ]),

            Section::make('Evidencia adjunta')
                ->icon('heroicon-o-photo')
                ->visible(fn (Pqrs $record): bool => $record->adjuntos()->exists())
                ->schema([
                    RepeatableEntry::make('adjuntos')
                        ->label('')
                        ->schema([
                            ImageEntry::make('ruta')
                                ->label('')
                                ->disk('public')
                                ->height(150)
                                ->extraImgAttributes(['style' => 'border-radius:8px;object-fit:cover;width:100%;']),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),

            Section::make('Historial de estados')
                ->icon('heroicon-o-clock')
                ->collapsible()
                ->schema([
                    RepeatableEntry::make('historial')
                        ->label('')
                        ->schema([
                            TextEntry::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
                            TextEntry::make('estado_anterior')->label('De')
                                ->formatStateUsing(fn (?string $state): string => $state ? (EstadoPqrs::tryFrom($state)?->label() ?? $state) : '—'),
                            TextEntry::make('estado_nuevo')->label('A')
                                ->formatStateUsing(fn (string $state): string => EstadoPqrs::tryFrom($state)?->label() ?? $state),
                            TextEntry::make('observacion')->label('Observación')->placeholder('—'),
                            TextEntry::make('usuario.name')->label('Por')->placeholder('Sistema'),
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
                TextColumn::make('vencimiento')
                    ->label('Vencimiento')
                    ->badge()
                    ->getStateUsing(function (Pqrs $record): string {
                        $s = $record->semaforo;
                        if ($s === null) {
                            return 'Sin plazo';
                        }
                        if ($s === 'cumplida') {
                            return 'Atendida';
                        }
                        $d = (int) $record->dias_restantes;
                        return $d < 0 ? 'Vencida ' . abs($d) . 'd' : $d . ' día(s)';
                    })
                    ->color(fn (Pqrs $record): string => match ($record->semaforo) {
                        'verde'  => 'success',
                        'ambar'  => 'warning',
                        'rojo'   => 'danger',
                        default  => 'gray',
                    })
                    ->icon(fn (Pqrs $record): ?string => $record->semaforo === 'rojo' ? 'heroicon-m-exclamation-triangle' : null)
                    ->tooltip(fn (Pqrs $record): ?string => $record->fecha_limite ? 'Vence: ' . $record->fecha_limite->format('d/m/Y') : null),
                TextColumn::make('nombre_ciudadano')->searchable(),
                TextColumn::make('elemento.rotulo')->label('Elemento')->searchable(),
                TextColumn::make('funcionario.name')->label('Asignado a')->placeholder('Sin asignar'),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->label('Radicada'),
            ])
            ->filters([
                SelectFilter::make('estado')->options(EstadoPqrs::opciones()),
                SelectFilter::make('tipo_solicitud')->label('Tipo')->options(TipoSolicitud::opciones()),
                Filter::make('vencidas')
                    ->label('Solo vencidas')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query
                        ->whereNotIn('estado', ['respondida', 'cerrada'])
                        ->whereNotNull('fecha_limite')
                        ->where('fecha_limite', '<', now())),
                Filter::make('sin_asignar')
                    ->label('Sin asignar')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->whereNull('funcionario_id')),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('responder')
                    ->label('Responder')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn (Pqrs $record): bool => ! in_array($record->estado, ['respondida', 'cerrada'], true))
                    ->form([
                        Textarea::make('accion_tomada')->label('Respuesta al ciudadano')->required()->rows(4),
                        Textarea::make('observacion')->label('Observación interna (opcional)')->rows(2),
                    ])
                    ->action(function (Pqrs $record, array $data): void {
                        $record->cambiarEstado(
                            \App\Enums\EstadoPqrs::Respondida,
                            $data['observacion'] ?? null,
                            auth()->id(),
                            $data['accion_tomada'],
                        );
                        $record->notify(new \App\Notifications\PqrsActualizadaNotification($record));
                        cache()->forget('nav_badge_pqrs');
                        Notification::make()->title('PQRS respondida')->success()->send();
                    }),
                Action::make('asignar')
                    ->label('Asignar')
                    ->icon('heroicon-o-user-plus')
                    ->color('gray')
                    ->fillForm(fn (Pqrs $record): array => ['funcionario_id' => $record->funcionario_id])
                    ->form([
                        Select::make('funcionario_id')
                            ->label('Funcionario responsable')
                            ->options(fn (): array => \App\Models\User::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->action(function (Pqrs $record, array $data): void {
                        $record->update(['funcionario_id' => $data['funcionario_id']]);
                    }),
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
