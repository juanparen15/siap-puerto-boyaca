<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>PQRS Actualizada</title></head>
<body style="font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;">
    <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; overflow:hidden;">
        <div style="background:#1B6B2F; padding:24px; text-align:center;">
            <h1 style="color:#fff; margin:0; font-size:20px;">Alcaldía de Puerto Boyacá</h1>
            <p style="color:#d1fae5; margin:4px 0 0;">SIAP — Alumbrado Público</p>
        </div>
        <div style="padding:32px;">
            <h2 style="color:#1B6B2F;">Su PQRS ha sido actualizada</h2>
            <p>Número de radicado: <strong>{{ $pqrs->radicado }}</strong></p>
            <p>Tipo: {{ ucfirst($pqrs->tipo_solicitud) }}</p>
            <p>Estado: <strong>{{ ucfirst(str_replace('_', ' ', $pqrs->estado)) }}</strong></p>
            @if($pqrs->accion_tomada)
            <p>Acción tomada: {{ $pqrs->accion_tomada }}</p>
            @endif
            <p style="margin-top:24px;">Puede consultar el estado de su solicitud en cualquier momento:</p>
            <a href="{{ url('/pqrs/consultar') }}" style="display:inline-block; background:#1B6B2F; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none;">Consultar mi PQRS</a>
        </div>
        <div style="background:#f9fafb; padding:16px; text-align:center; font-size:12px; color:#6b7280;">
            Alcaldía Municipal de Puerto Boyacá — Secretaría de Obras Públicas
        </div>
    </div>
</body>
</html>
