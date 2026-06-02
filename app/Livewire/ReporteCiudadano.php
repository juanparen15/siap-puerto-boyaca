<?php

namespace App\Livewire;

use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use App\Models\PqrsHistorial;
use App\Notifications\PqrsRadicadaNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ReporteCiudadano extends Component
{
    /** Tipos de problema según RETILAP 580.1 */
    public const TIPOS_PROBLEMA = [
        'luminaria_apagada'      => 'Luminaria apagada',
        'luminaria_intermitente' => 'Luminaria intermitente',
        'poste_danado'           => 'Poste dañado / inclinado',
        'cable_expuesto'         => 'Cable expuesto / riesgo eléctrico',
        'vandalismo'             => 'Luminaria vandalizada',
        'otro'                   => 'Otro',
    ];

    // Elemento seleccionado en el mapa
    public ?int $elementoId = null;
    public ?string $elementoLabel = null;
    public ?float $latitud = null;
    public ?float $longitud = null;

    public bool $mostrarForm = false;

    // Campos del formulario
    public string $tipoProblema = '';
    public string $descripcion = '';
    public string $nombre = '';
    public string $cedula = '';
    public string $telefono = '';
    public string $email = '';

    public ?string $radicadoGenerado = null;

    /** Disparado desde el popup del mapa (JS → Livewire). */
    #[On('seleccionar-elemento')]
    public function seleccionarElemento(int $id, string $tipo = '', string $rotulo = '', ?float $lat = null, ?float $lng = null): void
    {
        $this->resetReporte();
        $this->elementoId    = $id;
        $this->elementoLabel = trim(($rotulo ?: ucfirst($tipo)) . ($rotulo ? '' : " #{$id}")) ?: "Elemento #{$id}";
        $this->latitud       = $lat;
        $this->longitud      = $lng;
        $this->mostrarForm   = true;
    }

    public function cerrarForm(): void
    {
        $this->mostrarForm = false;
    }

    protected function rules(): array
    {
        return [
            'tipoProblema' => ['required', 'in:' . implode(',', array_keys(self::TIPOS_PROBLEMA))],
            'descripcion'  => ['required', 'string', 'min:10', 'max:2000'],
            'nombre'       => ['nullable', 'string', 'max:150'],
            'cedula'       => ['nullable', 'string', 'max:20'],
            'telefono'     => ['nullable', 'regex:/^[0-9]{7,10}$/'],
            'email'        => ['nullable', 'email', 'max:150'],
        ];
    }

    protected function messages(): array
    {
        return [
            'tipoProblema.required' => 'Selecciona el tipo de problema.',
            'descripcion.required'  => 'Describe el problema.',
            'descripcion.min'       => 'La descripción debe tener al menos 10 caracteres.',
            'telefono.regex'        => 'El teléfono debe tener entre 7 y 10 dígitos.',
        ];
    }

    public function enviarReporte(): void
    {
        $this->validate();

        $tipoLabel = self::TIPOS_PROBLEMA[$this->tipoProblema] ?? 'Otro';

        try {
            $pqrs = DB::transaction(function () use ($tipoLabel) {
                $pqrs = Pqrs::crearConRadicado([
                    'numero_cedula'    => trim($this->cedula) ?: 'ANONIMO',
                    'nombre_ciudadano' => trim($this->nombre) ?: 'Ciudadano anónimo',
                    'elemento_id'      => $this->elementoId,
                    'latitud'          => $this->latitud,
                    'longitud'         => $this->longitud,
                    'tipo_solicitud'   => 'queja',
                    'descripcion'      => "[{$tipoLabel}] " . trim($this->descripcion),
                    'email'            => trim($this->email) ?: null,
                    'telefono'         => trim($this->telefono) ?: null,
                    'estado'           => 'radicada',
                ]);

                PqrsHistorial::create([
                    'pqrs_id'         => $pqrs->id,
                    'estado_anterior' => null,
                    'estado_nuevo'    => 'radicada',
                    'observacion'     => 'Reporte radicado por ciudadano desde el mapa público',
                ]);

                return $pqrs;
            });

            try {
                $pqrs->notify(new PqrsRadicadaNotification($pqrs));
            } catch (\Throwable $e) {
                Log::warning('No se pudo enviar la notificación del reporte: ' . $e->getMessage());
            }

            $this->radicadoGenerado = $pqrs->radicado;
            $this->mostrarForm = false;
        } catch (\Throwable $e) {
            Log::error('Error al radicar reporte ciudadano: ' . $e->getMessage());
            $this->addError('general', 'Ocurrió un error al enviar tu reporte. Por favor intenta de nuevo.');
        }
    }

    public function reiniciar(): void
    {
        $this->resetReporte();
        $this->radicadoGenerado = null;
    }

    protected function resetReporte(): void
    {
        $this->reset([
            'tipoProblema', 'descripcion', 'nombre', 'cedula', 'telefono', 'email',
        ]);
        $this->resetErrorBag();
    }

    public function render(): View
    {
        return view('livewire.reporte-ciudadano', [
            'tiposProblema' => self::TIPOS_PROBLEMA,
        ])->extends('public.layouts.app')->section('content');
    }
}
