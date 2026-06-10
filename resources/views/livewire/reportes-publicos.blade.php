<div>
    {{-- Encabezado --}}
    <section class="page-title-area">
        <div class="container large">
            <div class="page-title-area-inner section-spacing-top">
                <div class="page-title-wrapper">
                    <h2 class="page-title fade-anim">Reportes</h2>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container large">
            <div class="section-header fade-anim" style="padding-top:24px;">
                <div class="section-title-wrapper">
                    <div class="subtitle-wrapper"><span class="section-subtitle">Transparencia</span></div>
                    <div class="title-wrapper">
                        <h2 class="section-title font-instrumentsans-medium">Reportes del alumbrado público</h2>
                    </div>
                </div>
                <div class="text-wrapper">
                    <p class="text">Municipio de Puerto Boyacá — Información pública del servicio de alumbrado.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom">
        <div class="container large">

            {{-- Tarjetas resumen --}}
            @php
                $operativos = $por_estado['operativa'] ?? 0;
                $pctOperativos = $total_elementos > 0 ? round($operativos / $total_elementos * 100, 1) : 0;
                $pqrsPendientes = ($pqrs_por_estado['radicada'] ?? 0) + ($pqrs_por_estado['en_proceso'] ?? 0);
            @endphp
            <div class="row fade-anim" style="--bs-gutter-y:24px;margin-bottom:32px;">
                @foreach ([
                    ['Total elementos', $total_elementos, '#3366CC', null],
                    ['Operativos', $pctOperativos.'%', '#16a34a', $operativos.' de '.$total_elementos],
                    ['PQRS pendientes', $pqrsPendientes, '#f59e0b', null],
                    ['PQRS resueltas', ($pqrs_por_estado['resuelta'] ?? 0), '#3366CC', null],
                ] as [$label, $valor, $color, $sub])
                    <div class="col-lg-3 col-md-6">
                        <div class="siap-panel" style="padding:30px 28px;height:100%;">
                            <p class="siap-stat-num" style="font-size:46px;color:var(--siap-ink);margin:0;line-height:1;">{{ $valor }}</p>
                            <p style="font-size:13px;font-weight:600;color:var(--siap-ink);margin:12px 0 0;">{{ $label }}</p>
                            @if ($sub)<p style="font-size:12px;color:#94a3b8;margin:3px 0 0;">{{ $sub }}</p>@endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Inventario por tipo / estado --}}
            <div class="row fade-anim" style="--bs-gutter-y:24px;margin-bottom:32px;">
                <div class="col-lg-6">
                    <div class="siap-panel" style="padding:30px;height:100%;">
                        <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:26px;color:var(--siap-ink);margin:0 0 18px;">Elementos por tipo</h3>
                        <table class="siap-table">
                            <thead><tr><th>Tipo</th><th style="text-align:right;">Cantidad</th></tr></thead>
                            <tbody>
                                @forelse ($por_tipo as $tipo => $cantidad)
                                    <tr><td data-label="Tipo" style="text-transform:capitalize;">{{ str_replace('_', ' ', $tipo) }}</td><td data-label="Cantidad" style="text-align:right;font-weight:600;">{{ $cantidad }}</td></tr>
                                @empty
                                    <tr><td colspan="2" style="text-align:center;color:#94a3b8;">Sin datos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="siap-panel" style="padding:30px;height:100%;">
                        <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:26px;color:var(--siap-ink);margin:0 0 18px;">Elementos por estado</h3>
                        <table class="siap-table">
                            <thead><tr><th>Estado</th><th style="text-align:right;">Cantidad</th></tr></thead>
                            <tbody>
                                @forelse ($por_estado as $estado => $cantidad)
                                    <tr><td data-label="Estado" style="text-transform:capitalize;">{{ str_replace('_', ' ', $estado) }}</td><td data-label="Cantidad" style="text-align:right;font-weight:600;">{{ $cantidad }}</td></tr>
                                @empty
                                    <tr><td colspan="2" style="text-align:center;color:#94a3b8;">Sin datos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Facturación reciente --}}
            <div class="siap-panel fade-anim" style="padding:30px;margin-bottom:32px;">
                <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:26px;color:var(--siap-ink);margin:0 0 18px;">Facturación reciente (últimos 6 períodos)</h3>
                <div style="overflow-x:auto;">
                    <table class="siap-table">
                        <thead><tr><th>Período</th><th>Empresa</th><th>kWh consumidos</th><th>Valor facturado</th><th>Estado</th></tr></thead>
                        <tbody>
                            @forelse ($facturacion_reciente as $f)
                                <tr>
                                    <td data-label="Período">{{ $f->periodo }}</td>
                                    <td data-label="Empresa">{{ $f->empresa_energetica ?? '—' }}</td>
                                    <td data-label="kWh consumidos">{{ number_format($f->kwh_consumidos ?? 0, 0, ',', '.') }}</td>
                                    <td data-label="Valor facturado">$ {{ number_format($f->valor_facturado ?? 0, 0, ',', '.') }}</td>
                                    <td data-label="Estado">
                                        <span style="padding:2px 10px;border-radius:9999px;font-size:12px;font-weight:600;
                                            {{ $f->estado === 'pagada' ? 'background:#dcfce7;color:#15803d;' : ($f->estado === 'vencida' ? 'background:#fee2e2;color:#b91c1c;' : 'background:#fef9c3;color:#a16207;') }}">
                                            {{ ucfirst($f->estado ?? 'pendiente') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" style="text-align:center;color:#94a3b8;">Sin registros de facturación</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recaudos recientes --}}
            <div class="siap-panel fade-anim" style="padding:30px;margin-bottom:32px;">
                <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:26px;color:var(--siap-ink);margin:0 0 18px;">Recaudos recientes (últimos 6)</h3>
                <div style="overflow-x:auto;">
                    <table class="siap-table">
                        <thead><tr><th>Período</th><th>Concepto</th><th>Valor</th><th>Fuente</th></tr></thead>
                        <tbody>
                            @forelse ($recaudos_recientes as $r)
                                <tr>
                                    <td data-label="Período">{{ $r->periodo ?? '—' }}</td>
                                    <td data-label="Concepto">{{ $r->concepto ?? '—' }}</td>
                                    <td data-label="Valor">$ {{ number_format($r->valor_recaudado ?? 0, 0, ',', '.') }}</td>
                                    <td data-label="Fuente">{{ $r->fuente_pago ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" style="text-align:center;color:#94a3b8;">Sin registros de recaudo</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Nota institucional --}}
            <div class="siap-panel fade-anim" style="padding:30px;background:rgba(51,102,204,.04);border-color:rgba(51,102,204,.2);">
                <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:24px;color:var(--siap-blue);margin:0 0 10px;">Información pública</h3>
                <p style="font-size:14px;color:#475569;margin:0;">
                    La Alcaldía de Puerto Boyacá publica el inventario del sistema de alumbrado público, los indicadores
                    de calidad del servicio y la información financiera asociada a su prestación. Los datos presentados
                    corresponden al Sistema de Información de Alumbrado Público (SIAP) municipal y son actualizados
                    periódicamente por la Secretaría de Obras Públicas.
                </p>
            </div>
        </div>
    </section>
</div>
