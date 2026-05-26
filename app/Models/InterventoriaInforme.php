<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterventoriaInforme extends Model
{
    protected $fillable = [
        'tipo_informe', 'periodo', 'aspectos_evaluados', 'cumplimiento_indices',
        'costos_operacion', 'recomendaciones', 'compromisos_siguiente',
        'usuario_id', 'fecha_informe',
    ];

    protected $casts = ['fecha_informe' => 'date'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
