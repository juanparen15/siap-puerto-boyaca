<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes de Interventoría</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; color: #1B6B2F; margin-bottom: 4px; }
        p.subtitle { font-size: 10px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background-color: #1B6B2F; color: #fff; padding: 7px 6px; text-align: left; font-size: 10px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; vertical-align: top; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f9fafb; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-mensual { background: #dbeafe; color: #1e40af; }
        .badge-trimestral { background: #dcfce7; color: #166534; }
        .badge-anual { background: #fef9c3; color: #854d0e; }
        footer { margin-top: 30px; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <h1>Informes de Interventoría — Alumbrado Público</h1>
    <p class="subtitle">Municipio de Puerto Boyacá &mdash; SIAP &mdash; Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Período</th>
                <th>Fecha Informe</th>
                <th>Responsable</th>
                <th>Cumplimiento</th>
                <th>Recomendaciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($informes as $informe)
            <tr>
                <td>{{ $informe->id }}</td>
                <td>
                    <span class="badge badge-{{ $informe->tipo_informe }}">
                        {{ ucfirst($informe->tipo_informe ?? '—') }}
                    </span>
                </td>
                <td>{{ $informe->periodo ?? '—' }}</td>
                <td>{{ $informe->fecha_informe?->format('d/m/Y') ?? '—' }}</td>
                <td>{{ $informe->usuario?->name ?? '—' }}</td>
                <td>{{ $informe->cumplimiento_indices ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($informe->recomendaciones ?? '', 120) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; color:#9ca3af; padding: 20px;">
                    No hay informes registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Sistema de Información de Alumbrado Público (SIAP) &mdash; Alcaldía de Puerto Boyacá
    </footer>
</body>
</html>
