<?php

namespace App\Filament\Pages;

use App\Models\Configuracion as Ajuste;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class Configuracion extends Page
{
    protected static string|BackedEnum|null $navigationIcon = null;

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Configuración';

    protected static ?string $title = 'Configuración del Sistema';

    protected string $view = 'filament.pages.configuracion';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill([
            'whatsapp_driver'      => Ajuste::get('whatsapp_driver', 'twilio'),
            'twilio_sid'           => Ajuste::get('twilio_sid', ''),
            'twilio_token'         => Ajuste::get('twilio_token', ''),
            'twilio_whatsapp_from' => Ajuste::get('twilio_whatsapp_from', '+14155238886'),
            'meta_whatsapp_token'  => Ajuste::get('meta_whatsapp_token', ''),
            'meta_phone_number_id' => Ajuste::get('meta_phone_number_id', ''),
            'gemini_api_key'       => Ajuste::get('gemini_api_key', ''),
            'mail_from_address'    => Ajuste::get('mail_from_address', 'siap@puertoboyaca-boyaca.gov.co'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Notificaciones por WhatsApp')
                    ->description('Proveedor y credenciales para enviar avisos por WhatsApp a los ciudadanos.')
                    ->icon(Heroicon::ChatBubbleLeftRight)
                    ->columns(2)
                    ->schema([
                        Select::make('whatsapp_driver')
                            ->label('Proveedor')
                            ->options([
                                'twilio' => 'Twilio',
                                'meta'   => 'Meta (WhatsApp Business API)',
                            ])
                            ->native(false)
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('twilio_sid')
                            ->label('Twilio Account SID')
                            ->visible(fn (Get $get): bool => $get('whatsapp_driver') === 'twilio'),
                        TextInput::make('twilio_token')
                            ->label('Twilio Auth Token')
                            ->visible(fn (Get $get): bool => $get('whatsapp_driver') === 'twilio'),
                        TextInput::make('twilio_whatsapp_from')
                            ->label('Número remitente (From)')
                            ->placeholder('+14155238886')
                            ->prefixIcon(Heroicon::Phone)
                            ->visible(fn (Get $get): bool => $get('whatsapp_driver') === 'twilio'),

                        TextInput::make('meta_whatsapp_token')
                            ->label('Token de acceso de Meta')
                            ->visible(fn (Get $get): bool => $get('whatsapp_driver') === 'meta'),
                        TextInput::make('meta_phone_number_id')
                            ->label('Phone Number ID')
                            ->visible(fn (Get $get): bool => $get('whatsapp_driver') === 'meta'),
                    ]),

                Section::make('Inteligencia Artificial')
                    ->description('Clave de Google Gemini para funciones asistidas por IA.')
                    ->icon(Heroicon::Sparkles)
                    ->schema([
                        TextInput::make('gemini_api_key')
                            ->label('Gemini API Key')
                            ->columnSpanFull(),
                    ]),

                Section::make('Correo electrónico')
                    ->description('Dirección desde la que se envían los correos del sistema.')
                    ->icon(Heroicon::Envelope)
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->label('Correo remitente')
                            ->email()
                            ->prefixIcon(Heroicon::AtSymbol)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar configuración')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $clave => $valor) {
            Ajuste::set($clave, $valor);
        }

        Notification::make()
            ->title('Configuración guardada correctamente')
            ->success()
            ->send();
    }
}
