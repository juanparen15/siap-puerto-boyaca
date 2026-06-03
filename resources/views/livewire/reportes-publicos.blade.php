<div class="min-h-screen pb-16 pt-28 lg:pt-32">
    <div class="mx-auto max-w-7xl px-4">

        <!-- Header -->
        <div class="mb-10">
            <span class="corp-eyebrow">Transparencia</span>
            <h1 class="font-display mt-4 text-4xl font-bold tracking-tight md:text-5xl" style="color:var(--siap-ink)">Reportes del alumbrado público</h1>
            <p class="mt-2 text-slate-500">Municipio de Puerto Boyacá — Información pública del servicio de alumbrado</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="corp-card p-6 border-l-4 border-[#1B6B2F]">
                <p class="text-sm text-slate-500 uppercase tracking-wide">Total Elementos</p>
                <p class="font-display text-4xl font-bold text-[#1B6B2F] mt-1">{{ $total_elementos }}</p>
            </div>

            @php
                $operativos = $por_estado['operativa'] ?? 0;
                $pctOperativos = $total_elementos > 0 ? round($operativos / $total_elementos * 100, 1) : 0;
            @endphp
            <div class="corp-card p-6 border-l-4 border-green-500">
                <p class="text-sm text-slate-500 uppercase tracking-wide">Operativos</p>
                <p class="font-display text-4xl font-bold text-green-600 mt-1">{{ $pctOperativos }}%</p>
                <p class="text-xs text-slate-400 mt-1">{{ $operativos }} de {{ $total_elementos }}</p>
            </div>

            @php
                $pqrsPendientes = ($pqrs_por_estado['radicada'] ?? 0) + ($pqrs_por_estado['en_proceso'] ?? 0);
            @endphp
            <div class="corp-card p-6 border-l-4 border-yellow-500">
                <p class="text-sm text-slate-500 uppercase tracking-wide">PQRS Pendientes</p>
                <p class="font-display text-4xl font-bold text-yellow-600 mt-1">{{ $pqrsPendientes }}</p>
            </div>

            <div class="corp-card p-6 border-l-4 border-blue-500">
                <p class="text-sm text-slate-500 uppercase tracking-wide">PQRS Resueltas</p>
                <p class="font-display text-4xl font-bold text-blue-600 mt-1">{{ $pqrs_por_estado['resuelta'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Inventario por Tipo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Elementos por Tipo</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2">Tipo</th>
                            <th class="pb-2 text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($por_tipo as $tipo => $cantidad)
                        <tr class="border-b last:border-0">
                            <td class="py-2 capitalize">{{ str_replace('_', ' ', $tipo) }}</td>
                            <td class="py-2 text-right font-medium">{{ $cantidad }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-400">Sin datos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Elementos por Estado</h2>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2">Estado</th>
                            <th class="pb-2 text-right">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($por_estado as $estado => $cantidad)
                        <tr class="border-b last:border-0">
                            <td class="py-2 capitalize">{{ str_replace('_', ' ', $estado) }}</td>
                            <td class="py-2 text-right font-medium">{{ $cantidad }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-400">Sin datos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Facturación Reciente -->
        <div class="bg-white rounded-xl shadow p-6 mb-10">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Facturación Reciente (últimos 6 períodos)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2 pr-4">Período</th>
                            <th class="pb-2 pr-4">Empresa</th>
                            <th class="pb-2 pr-4">kWh Consumidos</th>
                            <th class="pb-2 pr-4">Valor Facturado</th>
                            <th class="pb-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facturacion_reciente as $f)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $f->periodo }}</td>
                            <td class="py-2 pr-4">{{ $f->empresa_energetica ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ number_format($f->kwh_consumidos ?? 0, 0, ',', '.') }}</td>
                            <td class="py-2 pr-4">$ {{ number_format($f->valor_facturado ?? 0, 0, ',', '.') }}</td>
                            <td class="py-2">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $f->estado === 'pagada' ? 'bg-green-100 text-green-700' : ($f->estado === 'vencida' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($f->estado ?? 'pendiente') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">Sin registros de facturación</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recaudos Recientes -->
        <div class="bg-white rounded-xl shadow p-6 mb-10">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Recaudos Recientes (últimos 6)</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 border-b">
                            <th class="pb-2 pr-4">Período</th>
                            <th class="pb-2 pr-4">Concepto</th>
                            <th class="pb-2 pr-4">Valor</th>
                            <th class="pb-2">Fuente</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recaudos_recientes as $r)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-4">{{ $r->periodo ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ $r->concepto ?? '—' }}</td>
                            <td class="py-2 pr-4">$ {{ number_format($r->valor_recaudado ?? 0, 0, ',', '.') }}</td>
                            <td class="py-2">{{ $r->fuente_pago ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">Sin registros de recaudo</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nota institucional -->
        <div class="bg-[#1B6B2F]/5 border border-[#1B6B2F]/20 rounded-xl p-6">
            <h3 class="font-semibold text-[#1B6B2F] mb-2">Información pública</h3>
            <p class="text-sm text-gray-600">
                La Alcaldía de Puerto Boyacá publica el inventario del sistema de alumbrado público, los indicadores
                de calidad del servicio y la información financiera asociada a su prestación. Los datos presentados
                corresponden al Sistema de Información de Alumbrado Público (SIAP) municipal y son actualizados
                periódicamente por la Secretaría de Obras Públicas.
            </p>
        </div>
    </div>
</div>
