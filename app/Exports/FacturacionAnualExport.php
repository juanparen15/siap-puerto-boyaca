<?php
namespace App\Exports;

use App\Models\FacturacionPeriodo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FacturacionAnualExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return FacturacionPeriodo::query()->orderBy('periodo', 'desc');
    }

    public function headings(): array
    {
        return ['ID', 'Período', 'Empresa Energética', 'kWh Consumidos', 'Valor Facturado', 'Valor Pagado', 'Fecha Factura', 'Fecha Vencimiento', 'Fecha Pago', 'Estado', 'Extraído por IA'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->periodo,
            $row->empresa_energetica,
            $row->kwh_consumidos,
            $row->valor_facturado,
            $row->valor_pagado,
            $row->fecha_factura?->format('d/m/Y'),
            $row->fecha_vencimiento?->format('d/m/Y'),
            $row->fecha_pago?->format('d/m/Y'),
            $row->estado,
            $row->extraido_por_ia ? 'Sí' : 'No',
        ];
    }
}
