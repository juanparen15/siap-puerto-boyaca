<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanciamientoRecurso extends Model
{
    protected $fillable = [
        'fuente', 'tipo_recurso', 'valor', 'destinacion', 'fecha_recepcion', 'observaciones',
    ];

    protected $casts = ['fecha_recepcion' => 'date'];
}
