@php
    /** @var \App\Models\Pqrs $pqrs */
    /** @var \App\Enums\EstadoPqrs $estado */
    $tipo = \App\Enums\TipoSolicitud::tryFrom($pqrs->tipo_solicitud)?->label() ?? $pqrs->tipo_solicitud;
    $titulo = match ($estado->value) {
        'radicada'   => 'Hemos recibido tu solicitud',
        'en_tramite' => 'Tu solicitud está en trámite',
        'respondida' => 'Tu solicitud fue respondida',
        'cerrada'    => 'Tu solicitud fue cerrada',
        default      => 'Actualización de tu solicitud',
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>{{ $titulo }}</title></head>
<body style="margin:0;font-family:Arial,Helvetica,sans-serif;background:#eef1f6;padding:24px;">
    <div style="max-width:600px;margin:auto;background:#fff;border-radius:14px;overflow:hidden;border:1px solid #e6eaf2;">

        <div style="height:6px;background:linear-gradient(90deg,#3366CC,#6c8cff,#8a7bff);"></div>

        <div style="padding:24px 28px;border-bottom:1px solid #eef1f6;display:flex;align-items:center;gap:14px;">
            <img src="{{ url('images/LOGO ALCALDIA.png') }}" alt="Alcaldía de Puerto Boyacá" style="height:56px;width:auto;">
            <div>
                <div style="font-size:18px;font-weight:bold;color:#0c2a43;">SIAP · Alumbrado Público</div>
                <div style="font-size:13px;color:#64748b;">Alcaldía de Puerto Boyacá</div>
            </div>
        </div>

        <div style="padding:30px 28px;">
            <p style="font-size:11px;font-weight:bold;letter-spacing:1.5px;text-transform:uppercase;color:#3366CC;margin:0 0 6px;">Seguimiento PQRS</p>
            <h1 style="font-size:22px;color:#0c2a43;margin:0 0 8px;">{{ $titulo }}</h1>
            <p style="font-size:14px;color:#475569;margin:0 0 20px;">{{ $estado->descripcion() }}</p>

            <table style="width:100%;border-collapse:collapse;font-size:14px;color:#0c2a43;">
                <tr><td style="padding:9px 0;color:#64748b;border-bottom:1px solid #eef1f6;width:42%;">Radicado</td><td style="padding:9px 0;font-weight:bold;text-align:right;border-bottom:1px solid #eef1f6;">{{ $pqrs->radicado }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b;border-bottom:1px solid #eef1f6;">Tipo</td><td style="padding:9px 0;font-weight:bold;text-align:right;border-bottom:1px solid #eef1f6;">{{ $tipo }}</td></tr>
                <tr><td style="padding:9px 0;color:#64748b;border-bottom:1px solid #eef1f6;">Estado</td><td style="padding:9px 0;font-weight:bold;text-align:right;border-bottom:1px solid #eef1f6;color:#3366CC;">{{ $estado->label() }}</td></tr>
                @if (! $estado->esFinal() && $pqrs->fecha_limite)
                    <tr><td style="padding:9px 0;color:#64748b;">Fecha límite de respuesta</td><td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $pqrs->fecha_limite->format('d/m/Y') }}</td></tr>
                @endif
            </table>

            @if ($estado->value === 'respondida' && $pqrs->accion_tomada)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px;margin-top:20px;">
                    <p style="font-size:11px;font-weight:bold;text-transform:uppercase;letter-spacing:1px;color:#15803d;margin:0 0 6px;">Respuesta de la entidad</p>
                    <p style="font-size:14px;color:#14532d;margin:0;">{{ $pqrs->accion_tomada }}</p>
                </div>
            @endif

            <div style="text-align:center;margin-top:28px;">
                <a href="{{ url('/pqrs/consultar') }}" style="display:inline-block;background:#3366CC;color:#fff;padding:12px 26px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:14px;">Consultar mi PQRS</a>
            </div>
        </div>

        <div style="background:#0c2a43;padding:16px 28px;text-align:center;font-size:12px;color:#9fb3c8;">
            SIAP · Puerto Boyacá — Secretaría de Obras Públicas<br>Desarrollado por el contrato 099 de 2026
        </div>
    </div>
</body>
</html>
