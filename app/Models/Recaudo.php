<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recaudo extends Model
{
    protected $fillable = [
        'periodo', 'concepto', 'valor_recaudado', 'fuente_pago', 'fecha_recaudo', 'observaciones',
    ];

    protected $casts = ['fecha_recaudo' => 'date'];
}
