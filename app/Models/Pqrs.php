<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Pqrs extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pqrs';

    protected $fillable = [
        'radicado', 'numero_cedula', 'elemento_id', 'latitud', 'longitud',
        'tipo_solicitud', 'descripcion', 'nombre_ciudadano', 'email',
        'telefono', 'estado', 'accion_tomada', 'fecha_respuesta', 'funcionario_id',
    ];

    protected $casts = [
        'fecha_respuesta' => 'datetime',
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

    public static function crearConRadicado(array $datos): static
    {
        $year = now()->year;
        $ultimo = static::whereYear('created_at', $year)->lockForUpdate()->count() + 1;
        $datos['radicado'] = 'PQRS-' . $year . '-' . str_pad($ultimo, 6, '0', STR_PAD_LEFT);
        return static::create($datos);
    }
}
