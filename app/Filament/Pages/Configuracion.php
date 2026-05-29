<?php
namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Notifications\Notification;

class Configuracion extends Page
{
    protected static \Filament\Support\Icons\Heroicon|\BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static \UnitEnum|string|null $navigationGroup = 'Administración';
    protected string $view = 'filament.pages.configuracion';
    protected static ?string $title = 'Configuración del Sistema';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function mount(): void
    {
        $this->data = [
            'whatsapp_driver'         => \App\Models\Configuracion::get('whatsapp_driver', 'twilio'),
            'twilio_sid'              => \App\Models\Configuracion::get('twilio_sid', ''),
            'twilio_token'            => \App\Models\Configuracion::get('twilio_token', ''),
            'twilio_whatsapp_from'    => \App\Models\Configuracion::get('twilio_whatsapp_from', '+14155238886'),
            'meta_whatsapp_token'     => \App\Models\Configuracion::get('meta_whatsapp_token', ''),
            'meta_phone_number_id'    => \App\Models\Configuracion::get('meta_phone_number_id', ''),
            'gemini_api_key'          => \App\Models\Configuracion::get('gemini_api_key', ''),
            'mail_from_address'       => \App\Models\Configuracion::get('mail_from_address', 'siap@puertoboyaca-boyaca.gov.co'),
        ];
    }

    public function save(): void
    {
        foreach ($this->data as $clave => $valor) {
            \App\Models\Configuracion::set($clave, $valor);
        }
        Notification::make()->title('Configuración guardada')->success()->send();
    }
}
