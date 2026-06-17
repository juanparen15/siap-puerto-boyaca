<?php

namespace App\Livewire;

use App\Enums\TipoSolicitud;
use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use App\Models\PqrsAdjunto;
use App\Models\PqrsHistorial;
use App\Enums\EstadoPqrs;
use App\Notifications\PqrsEstadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormularioPqrs extends Component
{
    use WithFileUploads;

    public int $paso = 1;

    // Radicar sin identificarse
    public bool $anonimo = false;

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

    /** Evidencia opcional (hasta 3 imágenes). */
    public array $fotos = [];

    // Step 3: confirmation
    public ?string $radicadoGenerado = null;
    public ?string $fechaLimiteTexto = null;
    public ?int $plazoDias = null;
    public ?string $tipoLabel = null;

    protected function rules(): array
    {
        return match ($this->paso) {
            1 => array_merge(
                $this->anonimo ? [] : [
                    'nombre_ciudadano' => 'required|min:3|max:150',
                    'numero_cedula' => 'required|digits_between:6,15',
                ],
                [
                    'email' => 'nullable|email|max:150',
                    'telefono' => 'nullable|regex:/^[0-9]{10}$/',
                ]
            ),
            2 => [
                'tipo_solicitud' => 'required|in:' . implode(',', array_keys(TipoSolicitud::opciones())),
                'descripcion' => 'required|min:20|max:2000',
                'fotos' => 'nullable|array|max:3',
                'fotos.*' => 'image|max:4096',
            ],
            default => [],
        };
    }

    protected function messages(): array
    {
        return [
            'nombre_ciudadano.required' => 'El nombre completo es obligatorio.',
            'nombre_ciudadano.min'      => 'El nombre debe tener al menos 3 caracteres.',
            'nombre_ciudadano.max'      => 'El nombre es demasiado largo.',
            'numero_cedula.required'    => 'El número de cédula es obligatorio.',
            'numero_cedula.digits_between' => 'La cédula debe tener entre 6 y 15 dígitos.',
            'email.email'               => 'El correo electrónico no es válido.',
            'email.max'                 => 'El correo electrónico es demasiado largo.',
            'telefono.regex'            => 'El teléfono debe tener 10 dígitos.',
            'tipo_solicitud.required'   => 'Selecciona el tipo de solicitud.',
            'tipo_solicitud.in'         => 'El tipo de solicitud no es válido.',
            'descripcion.required'      => 'La descripción es obligatoria.',
            'descripcion.min'           => 'La descripción debe tener al menos 20 caracteres.',
            'descripcion.max'           => 'La descripción no puede superar los 2000 caracteres.',
            'fotos.max'                 => 'Puedes adjuntar máximo 3 fotos.',
            'fotos.*.image'             => 'Cada archivo debe ser una imagen.',
            'fotos.*.max'               => 'Cada foto no puede superar los 4 MB.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'nombre_ciudadano' => 'nombre completo',
            'numero_cedula'    => 'número de cédula',
            'email'            => 'correo electrónico',
            'telefono'         => 'teléfono',
            'tipo_solicitud'   => 'tipo de solicitud',
            'descripcion'      => 'descripción',
        ];
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

    /** Selección de un punto del inventario desde el mapa (JS → Livewire). */
    public function seleccionarPunto(int $id, float $lat, float $lng): void
    {
        $this->elemento_id = $id;
        $this->latitud = $lat;
        $this->longitud = $lng;
    }

    /** Quitar la selección / el punto elegido. */
    public function limpiarPunto(): void
    {
        $this->elemento_id = null;
        $this->latitud = null;
        $this->longitud = null;
    }

    /** Quitar una foto antes de enviar. */
    public function quitarFoto(int $index): void
    {
        unset($this->fotos[$index]);
        $this->fotos = array_values($this->fotos);
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

        // Control anti-spam: máx. 3 radicados por IP cada 10 minutos.
        $key = 'pqrs-submit:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $minutos = (int) ceil(RateLimiter::availableIn($key) / 60);
            $this->addError('general', "Has radicado varias PQRS recientemente. Inténtalo de nuevo en {$minutos} minuto(s).");
            return;
        }

        try {
            $pqrs = \DB::transaction(function () {
                $pqrs = Pqrs::crearConRadicado([
                    'numero_cedula' => $this->anonimo ? 'ANONIMO' : $this->numero_cedula,
                    'elemento_id' => $this->elemento_id,
                    'latitud' => $this->latitud,
                    'longitud' => $this->longitud,
                    'tipo_solicitud' => $this->tipo_solicitud,
                    'descripcion' => $this->descripcion,
                    'nombre_ciudadano' => $this->anonimo ? 'Ciudadano anónimo' : $this->nombre_ciudadano,
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

                // Evidencia (fotos) opcional
                foreach ($this->fotos as $foto) {
                    $ruta = $foto->store('pqrs/' . $pqrs->id, 'public');
                    PqrsAdjunto::create([
                        'pqrs_id'         => $pqrs->id,
                        'ruta'            => $ruta,
                        'nombre_original' => $foto->getClientOriginalName(),
                        'mime'            => $foto->getMimeType(),
                        'tamano'          => $foto->getSize(),
                    ]);
                }

                return $pqrs;
            });

            // Registrar el intento exitoso para el control anti-spam
            RateLimiter::hit($key, 600);

            // Notify after transaction commits
            try {
                $pqrs->notify(new PqrsEstadoNotification($pqrs, EstadoPqrs::Radicada));
            } catch (\Throwable $e) {
                \Log::warning('No se pudo notificar la radicación PQRS: ' . $e->getMessage());
            }

            $this->radicadoGenerado = $pqrs->radicado;
            $this->fechaLimiteTexto = $pqrs->fecha_limite?->format('d/m/Y');
            $this->plazoDias = $pqrs->tipoCaso()?->diasHabiles();
            $this->tipoLabel = $pqrs->tipoCaso()?->label();
            $this->fotos = [];
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
