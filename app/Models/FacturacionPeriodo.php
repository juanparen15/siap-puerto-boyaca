<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturacionPeriodo extends Model
{
    protected $fillable = [
        'periodo', 'empresa_energetica', 'kwh_consumidos', 'valor_facturado',
        'valor_pagado', 'fecha_factura', 'fecha_vencimiento', 'fecha_pago',
        'estado', 'archivo_path', 'extraido_por_ia',
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_pago' => 'date',
        'extraido_por_ia' => 'boolean',
    ];
}
