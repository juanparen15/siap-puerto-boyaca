<?php
namespace App\Exports;

use App\Models\InfraestructuraElemento;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventarioExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return InfraestructuraElemento::query()->with('red');
    }

    public function headings(): array
    {
        return ['ID', 'Tipo', 'Rótulo', 'Marca', 'Potencia (W)', 'Estado', 'Clasificación', 'Red', 'Latitud', 'Longitud', 'Fecha Levantamiento'];
    }

    public function map($row): array
    {
        return [
            $row->id, $row->tipo, $row->rotulo, $row->marca, $row->potencia_w,
            $row->estado, $row->clasificacion, $row->red?->nombre,
            $row->latitud, $row->longitud, $row->fecha_levantamiento?->format('d/m/Y'),
        ];
    }
}
