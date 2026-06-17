<?php
namespace App\Models;

use App\Enums\EstadoPqrs;
use App\Enums\TipoSolicitud;
use App\Support\DiasHabiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class Pqrs extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pqrs';

    protected $fillable = [
        'radicado', 'numero_cedula', 'elemento_id', 'latitud', 'longitud',
        'tipo_solicitud', 'descripcion', 'nombre_ciudadano', 'email',
        'telefono', 'estado', 'accion_tomada', 'fecha_respuesta', 'fecha_limite', 'funcionario_id',
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
        'fecha_limite' => 'datetime',
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
    ];

    public function routeNotificationForMail(): ?string
    {
        return $this->email ?: null;
    }

    public function routeNotificationForWhatsapp(): ?string
    {
        return $this->telefono ?: null;
    }

    public function elemento(): BelongsTo
    {
        return $this->belongsTo(InfraestructuraElemento::class, 'elemento_id');
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'funcionario_id');
    }

    public function historial(): HasMany
    {
        return $this->hasMany(PqrsHistorial::class)->orderBy('created_at');
    }

    public function adjuntos(): HasMany
    {
        return $this->hasMany(PqrsAdjunto::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Enums (value objects sobre las columnas string)
    |--------------------------------------------------------------------------
    */
    public function estadoCaso(): ?EstadoPqrs
    {
        return EstadoPqrs::tryFrom((string) $this->estado);
    }

    public function tipoCaso(): ?TipoSolicitud
    {
        return TipoSolicitud::tryFrom((string) $this->tipo_solicitud);
    }

    /*
    |--------------------------------------------------------------------------
    | SLA (plazos / semáforo)
    |--------------------------------------------------------------------------
    */

    /** Días hábiles restantes (negativo si está vencida). Null si ya cerró o sin plazo. */
    public function getDiasRestantesAttribute(): ?int
    {
        if ($this->estadoCaso()?->esFinal()) {
            return null;
        }
        $limite = $this->fecha_limite;
        if (! $limite) {
            return null;
        }
        return DiasHabiles::entre(now(), $limite);
    }

    /** verde | ambar | rojo | cumplida | null(sin plazo). */
    public function getSemaforoAttribute(): ?string
    {
        if ($this->estadoCaso()?->esFinal()) {
            return 'cumplida';
        }
        $dias = $this->dias_restantes;
        if ($dias === null) {
            return null;
        }
        if ($dias < 0) {
            return 'rojo';
        }
        if ($dias <= (int) config('pqrs.umbral_alerta_dias', 3)) {
            return 'ambar';
        }
        return 'verde';
    }

    public function getEstaVencidaAttribute(): bool
    {
        return $this->semaforo === 'rojo';
    }

    /*
    |--------------------------------------------------------------------------
    | Máquina de estados
    |--------------------------------------------------------------------------
    */

    /**
     * Cambia el estado registrando historial. Las notificaciones se envían en
     * una fase posterior del flujo.
     */
    public function cambiarEstado(EstadoPqrs|string $nuevo, ?string $observacion = null, ?int $usuarioId = null, ?string $accionTomada = null): void
    {
        $nuevoValor = $nuevo instanceof EstadoPqrs ? $nuevo->value : $nuevo;
        $anterior = $this->estado;

        $this->estado = $nuevoValor;
        if ($accionTomada !== null && $accionTomada !== '') {
            $this->accion_tomada = $accionTomada;
        }
        if ($nuevoValor === EstadoPqrs::Respondida->value && ! $this->fecha_respuesta) {
            $this->fecha_respuesta = now();
        }
        $this->save();

        PqrsHistorial::create([
            'pqrs_id'         => $this->id,
            'usuario_id'      => $usuarioId,
            'estado_anterior' => $anterior,
            'estado_nuevo'    => $nuevoValor,
            'observacion'     => $observacion,
        ]);

        // Notificar al ciudadano (correo + WhatsApp si hay datos). No debe romper el flujo.
        try {
            $this->notify(new \App\Notifications\PqrsEstadoNotification($this, EstadoPqrs::from($nuevoValor)));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('No se pudo notificar el cambio de estado PQRS ' . $this->radicado . ': ' . $e->getMessage());
        }
    }

    public static function crearConRadicado(array $datos): static
    {
        $year = now()->year;
        $ultimo = static::whereYear('created_at', $year)->lockForUpdate()->count() + 1;
        $datos['radicado'] = 'PQRS-' . $year . '-' . str_pad($ultimo, 6, '0', STR_PAD_LEFT);

        // Fecha límite legal (días hábiles según el tipo)
        if (empty($datos['fecha_limite'])) {
            $dias = TipoSolicitud::tryFrom($datos['tipo_solicitud'] ?? '')?->diasHabiles();
            if ($dias) {
                $datos['fecha_limite'] = DiasHabiles::sumar(now(), $dias);
            }
        }

        return static::create($datos);
    }
}
