<?php

namespace App\Livewire;

use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use App\Models\PqrsHistorial;
use App\Notifications\PqrsRadicadaNotification;
use Illuminate\Http\Request;
use Livewire\Component;

class FormularioPqrs extends Component
{
    public int $paso = 1;

    // Step 1: citizen data
    public string $nombre_ciudadano = '';
    public string $numero_cedula = '';
    public string $email = '';
    public string $telefono = '';

    // Step 2: request details
    public string $tipo_solicitud = '';
    public string $descripcion = '';
    public ?int $elemento_id = null;
    public ?float $latitud = null;
    public ?float $longitud = null;

    // Step 3: confirmation
    public ?string $radicadoGenerado = null;

    protected function rules(): array
    {
        return match ($this->paso) {
            1 => [
                'nombre_ciudadano' => 'required|min:3|max:150',
                'numero_cedula' => 'required|digits_between:6,15',
                'email' => 'nullable|email|max:150',
                'telefono' => 'nullable|regex:/^[0-9]{10}$/',
            ],
            2 => [
                'tipo_solicitud' => 'required|in:peticion,queja,reclamo,solicitud',
                'descripcion' => 'required|min:20|max:2000',
            ],
            default => [],
        };
    }

    public function mount(Request $request): void
    {
        // Pre-fill from map click
        $this->elemento_id = $request->query('elemento_id') ? (int) $request->query('elemento_id') : null;
        if ($this->elemento_id) {
            $el = InfraestructuraElemento::find($this->elemento_id);
            $this->latitud = $el?->latitud !== null ? (float) $el->latitud : null;
            $this->longitud = $el?->longitud !== null ? (float) $el->longitud : null;
        }
    }

    public function siguiente(): void
    {
        $this->validate();
        $this->paso++;
    }

    public function anterior(): void
    {
        $this->paso = max(1, $this->paso - 1);
    }

    public function enviar(): void
    {
        if ($this->paso !== 2) {
            return;
        }

        $this->validate();

        try {
            $pqrs = \DB::transaction(function () {
                $pqrs = Pqrs::crearConRadicado([
                    'numero_cedula' => $this->numero_cedula,
                    'elemento_id' => $this->elemento_id,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'tipo_solicitud' => $this->tipo_solicitud,
                    'descripcion' => $this->descripcion,
                    'nombre_ciudadano' => $this->nombre_ciudadano,
                    'email' => $this->email ?: null,
                    'telefono' => $this->telefono ?: null,
                    'estado' => 'radicada',
                ]);

                PqrsHistorial::create([
                    'pqrs_id' => $pqrs->id,
                    'estado_anterior' => null,
                    'estado_nuevo' => 'radicada',
                    'observacion' => 'PQRS radicada por ciudadano',
                ]);

                return $pqrs;
            });

            // Notify after transaction commits
            $pqrs->notify(new PqrsRadicadaNotification($pqrs));

            $this->radicadoGenerado = $pqrs->radicado;
            $this->paso = 3;
        } catch (\Throwable $e) {
            \Log::error('Error al radicar PQRS: ' . $e->getMessage());
            $this->addError('general', 'Ocurrió un error al radicar su PQRS. Por favor intente de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.formulario-pqrs')
            ->extends('public.layouts.app')
            ->section('content');
    }
}
