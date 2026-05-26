<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InfraestructuraRed extends Model
{
    protected $fillable = [
        'nombre', 'tipo', 'uso', 'clasificacion', 'material',
        'calibre_conductores', 'tipo_instalacion', 'tipo_zona',
        'tipo_transformador', 'potencia_kva', 'tension_primaria_kv',
        'tension_secundaria_kv', 'observaciones',
    ];

    public function elementos(): HasMany
    {
        return $this->hasMany(InfraestructuraElemento::class, 'red_id');
    }
}
