<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    // El grupo 'Administración' ya tiene ícono (Filament v5 no permite ambos).
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    protected static ?string $modelLabel = 'funcionario';

    protected static ?string $pluralModelLabel = 'funcionarios';

    protected static ?string $navigationLabel = 'Funcionarios';

    protected static ?int $navigationSort = 90;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    protected static function rolLabel(string $name): string
    {
        return ucfirst(str_replace('_', ' ', $name));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del funcionario')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre completo')
                        ->required()
                        ->maxLength(150),
                    TextInput::make('email')
                        ->label('Correo electrónico')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(150),
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->helperText('En edición, déjala vacía para conservar la actual.')
                        ->maxLength(100),
                ]),

            Section::make('Roles y permisos')
                ->schema([
                    Select::make('roles')
                        ->label('Roles')
                        ->relationship('roles', 'name')
                        ->getOptionLabelFromRecordUsing(fn ($record): string => static::rolLabel($record->name))
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->required()
                        ->helperText('Define a qué módulos del panel tiene acceso el funcionario.'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable()->sortable(),
                TextColumn::make('email')->label('Correo')->searchable()->sortable()->icon('heroicon-m-envelope'),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::rolLabel($state))
                    ->color('info'),
                TextColumn::make('created_at')->label('Creado')->dateTime('d/m/Y')->sortable()->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (User $record): bool => $record->id !== auth()->id()),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}
