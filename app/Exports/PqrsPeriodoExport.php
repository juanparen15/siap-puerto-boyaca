<?php
namespace App\Exports;

use App\Models\Pqrs;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PqrsPeriodoExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Pqrs::query()->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return ['Radicado', 'Tipo Solicitud', 'Estado', 'Nombre Ciudadano', 'Número Cédula', 'Fecha Radicado', 'Fecha Respuesta', 'Acción Tomada'];
    }

    public function map($row): array
    {
        return [
            $row->radicado,
            $row->tipo_solicitud,
            $row->estado,
            $row->nombre_ciudadano,
            $row->numero_cedula,
            $row->created_at?->format('d/m/Y H:i'),
            $row->fecha_respuesta?->format('d/m/Y'),
            $row->accion_tomada,
        ];
    }
}
