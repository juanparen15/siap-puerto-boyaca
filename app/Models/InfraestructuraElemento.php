<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InfraestructuraElemento extends Model
{
    protected $fillable = [
        'tipo', 'rotulo', 'red_id', 'marca', 'tecnologia', 'potencia_w',
        'estado', 'tipo_poste', 'altura_poste_m', 'carga_rotura_kgf',
        'clasificacion', 'descripcion', 'observaciones',
        'latitud', 'longitud', 'fecha_levantamiento', 'globalid',
    ];

    protected $casts = [
        'latitud' => 'decimal:7',
        'longitud' => 'decimal:7',
        'fecha_levantamiento' => 'date',
    ];

    public function red(): BelongsTo
    {
        return $this->belongsTo(InfraestructuraRed::class, 'red_id');
    }

    public function pqrs(): HasMany
    {
        return $this->hasMany(Pqrs::class, 'elemento_id');
    }
}
