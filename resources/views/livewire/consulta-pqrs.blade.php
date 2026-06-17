<div>
    {{-- Encabezado --}}
    <section class="page-title-area">
        <div class="container large">
            <div class="page-title-area-inner section-spacing-top">
                <div class="page-title-wrapper">
                    <h2 class="page-title fade-anim">Consulta</h2>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container large">
            <div class="section-header fade-anim" style="padding-top:24px;text-align:center;">
                <div class="section-title-wrapper">
                    <div class="subtitle-wrapper"><span class="section-subtitle">Seguimiento</span></div>
                    <div class="title-wrapper">
                        <h2 class="section-title font-instrumentsans-medium">Consultar mi reporte</h2>
                    </div>
                </div>
                <div class="text-wrapper">
                    <p class="text">Consulta el estado de tu Petición, Queja, Reclamo o Solicitud.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom">
        <div class="container large">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- Búsqueda --}}
                    <div class="siap-panel fade-anim" style="padding:34px;margin-bottom:24px;">
                        <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:24px;color:var(--siap-ink);margin:0 0 18px;">Búsqueda</h3>

                        <div style="display:flex;gap:12px;margin-bottom:18px;flex-wrap:wrap;">
                            <button type="button" wire:click="$set('tipoBusqueda', 'radicado')"
                                class="siap-btn {{ $tipoBusqueda === 'radicado' ? '' : 'siap-btn-ghost' }}" style="flex:1;min-width:200px;">
                                Por número de radicado
                            </button>
                            <button type="button" wire:click="$set('tipoBusqueda', 'cedula')"
                                class="siap-btn {{ $tipoBusqueda === 'cedula' ? '' : 'siap-btn-ghost' }}" style="flex:1;min-width:200px;">
                                Por número de cédula
                            </button>
                        </div>

                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <input type="text" wire:model="busqueda" wire:keydown.enter="consultar"
                                placeholder="{{ $tipoBusqueda === 'radicado' ? 'Ej. PQRS-2026-000001' : 'Ej. 12345678' }}"
                                class="siap-input" style="flex:1;min-width:220px;">
                            <button type="button" wire:click="consultar" wire:loading.attr="disabled" class="siap-btn">
                                <span wire:loading.remove wire:target="consultar">Consultar</span>
                                <span wire:loading wire:target="consultar">Buscando…</span>
                            </button>
                        </div>
                    </div>

                    @if ($error)
                        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:14px;padding:16px;margin-bottom:24px;color:#b91c1c;font-size:14px;">
                            {{ $error }}
                        </div>
                    @endif

                    {{-- Resultado --}}
                    @if ($pqrs)
                        <div class="siap-panel" style="padding:34px;">
                            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:14px;">
                                <div>
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Número de radicado</p>
                                    <p class="siap-stat-num" style="font-size:26px;color:var(--siap-ink);margin:0;">{{ $pqrs->radicado }}</p>
                                </div>
                                @php
                                    $estado = $pqrs->estadoCaso();
                                    $estadoStyle = match($estado?->value) {
                                        'radicada'   => 'background:#dbeafe;color:#1e40af;border-color:#bfdbfe;',
                                        'en_tramite' => 'background:#fef3c7;color:#92400e;border-color:#fde68a;',
                                        'respondida' => 'background:#dcfce7;color:#166534;border-color:#bbf7d0;',
                                        'cerrada'    => 'background:#f1f5f9;color:#475569;border-color:#e2e8f0;',
                                        default      => 'background:#f1f5f9;color:#475569;border-color:#e2e8f0;',
                                    };
                                    $estadoLabel = $estado?->label() ?? ucfirst($pqrs->estado);
                                @endphp
                                <span style="display:inline-block;font-size:14px;font-weight:600;padding:6px 18px;border-radius:9999px;border:1px solid;{{ $estadoStyle }}">{{ $estadoLabel }}</span>
                            </div>

                            {{-- Estado / SLA --}}
                            @php
                                $sem = $pqrs->semaforo;
                                $semStyle = match($sem) {
                                    'verde'    => 'background:#dcfce7;color:#166534;',
                                    'ambar'    => 'background:#fef3c7;color:#92400e;',
                                    'rojo'     => 'background:#fee2e2;color:#b91c1c;',
                                    'cumplida' => 'background:#dbeafe;color:#1e40af;',
                                    default    => 'background:#f1f5f9;color:#475569;',
                                };
                                $diasRest = $pqrs->dias_restantes;
                            @endphp
                            <div style="margin-top:14px;display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                                @if ($sem === 'cumplida')
                                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;padding:5px 14px;border-radius:9999px;{{ $semStyle }}">Atendida</span>
                                @elseif ($sem !== null)
                                    <span style="display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:600;padding:5px 14px;border-radius:9999px;{{ $semStyle }}">
                                        @if ($diasRest < 0) Vencida hace {{ abs($diasRest) }} día(s) hábil(es)
                                        @else Quedan {{ $diasRest }} día(s) hábil(es) @endif
                                    </span>
                                    @if ($pqrs->fecha_limite)
                                        <span style="font-size:13px;color:#64748b;">Fecha límite: <strong>{{ $pqrs->fecha_limite->format('d/m/Y') }}</strong></span>
                                    @endif
                                @endif
                            </div>
                            @if ($estado?->descripcion())
                                <p style="margin:10px 0 0;color:#475569;font-size:14px;">{{ $estado->descripcion() }}</p>
                            @endif

                            <hr style="border:0;border-top:1px solid rgba(12,42,67,.08);margin:24px 0;">

                            <div class="row" style="--bs-gutter-y:18px;">
                                <div class="col-sm-6">
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Tipo de solicitud</p>
                                    <p style="font-size:14px;font-weight:600;color:var(--siap-ink);margin:0;">{{ $pqrs->tipoCaso()?->label() ?? str_replace('_', ' ', $pqrs->tipo_solicitud) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Fecha de radicación</p>
                                    <p style="font-size:14px;font-weight:600;color:var(--siap-ink);margin:0;">{{ $pqrs->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                @if ($pqrs->elemento)
                                    <div class="col-12">
                                        <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Elemento asociado</p>
                                        <p style="font-size:14px;font-weight:600;color:var(--siap-ink);margin:0;">
                                            {{ ucfirst($pqrs->elemento->tipo) }}@if ($pqrs->elemento->clasificacion) — {{ str_replace('_', ' ', $pqrs->elemento->clasificacion) }}@endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @if ($pqrs->accion_tomada)
                                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:14px;padding:16px 18px;margin-top:22px;">
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#15803d;font-weight:700;margin:0 0 6px;display:flex;align-items:center;gap:6px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                        Respuesta de la entidad
                                    </p>
                                    <p style="font-size:14px;color:#14532d;margin:0;">{{ $pqrs->accion_tomada }}</p>
                                    @if ($pqrs->fecha_respuesta)
                                        <p style="font-size:12px;color:#15803d;margin:8px 0 0;">Respondida el {{ $pqrs->fecha_respuesta->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            @endif

                            {{-- Evidencia adjunta por el ciudadano --}}
                            @if ($pqrs->adjuntos->isNotEmpty())
                                <div style="margin-top:22px;">
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Evidencia adjunta</p>
                                    <div style="display:flex;flex-wrap:wrap;gap:10px;">
                                        @foreach ($pqrs->adjuntos as $adj)
                                            <a href="{{ $adj->url }}" target="_blank" rel="noopener" style="display:block;width:96px;height:96px;border-radius:10px;overflow:hidden;border:1px solid rgba(12,42,67,.12);">
                                                <img src="{{ $adj->url }}" alt="Evidencia" style="width:100%;height:100%;object-fit:cover;display:block;">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($pqrs->latitud && $pqrs->longitud)
                                <div style="margin-top:22px;">
                                    <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Ubicación del reporte</p>
                                    <div id="pqrs-map" data-lat="{{ $pqrs->latitud }}" data-lng="{{ $pqrs->longitud }}"
                                         class="siap-map-canvas" style="height:220px;border-radius:14px;overflow:hidden;border:1px solid rgba(12,42,67,.12);" wire:ignore></div>
                                </div>
                            @endif

                            @if ($pqrs->historial->isNotEmpty())
                                <div style="margin-top:26px;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                                        <lord-icon src="https://cdn.lordicon.com/laobovmg.json" trigger="loop" delay="1500" stroke="bold"
                                            colors="primary:#3366CC,secondary:#22c55e" style="width:20px;height:20px"></lord-icon>
                                        <p style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#64748b;margin:0;">Historial de estados</p>
                                    </div>
                                    <ol style="position:relative;border-left:1px solid rgba(12,42,67,.12);margin-left:10px;padding:0;list-style:none;">
                                        @foreach ($pqrs->historial as $item)
                                            <li style="margin-left:24px;margin-bottom:22px;position:relative;">
                                                <span style="position:absolute;left:-30px;top:2px;width:18px;height:18px;background:var(--siap-blue);border-radius:9999px;border:4px solid #fff;box-shadow:0 0 0 1px rgba(12,42,67,.1);"></span>
                                                <div style="background:#f8fafc;border:1px solid rgba(12,42,67,.06);border-radius:12px;padding:12px 14px;">
                                                    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-bottom:4px;">
                                                        @if ($item->estado_anterior)
                                                            <span style="font-size:12px;color:#64748b;">{{ \App\Enums\EstadoPqrs::tryFrom($item->estado_anterior)?->label() ?? str_replace('_', ' ', $item->estado_anterior) }}</span>
                                                            <span style="color:#94a3b8;font-size:12px;">→</span>
                                                        @endif
                                                        <span style="font-size:12px;font-weight:700;color:var(--siap-blue);">{{ \App\Enums\EstadoPqrs::tryFrom($item->estado_nuevo)?->label() ?? str_replace('_', ' ', $item->estado_nuevo) }}</span>
                                                        @if ($item->usuario)<span style="font-size:12px;color:#94a3b8;">por {{ $item->usuario->name }}</span>@endif
                                                    </div>
                                                    @if ($item->observacion)<p style="font-size:14px;color:#334155;margin:0;">{{ $item->observacion }}</p>@endif
                                                    @if ($item->created_at)<p style="font-size:12px;color:#94a3b8;margin:4px 0 0;">{{ $item->created_at->format('d/m/Y H:i') }}</p>@endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        @vite(['resources/js/consulta-map.js'])
    @endpush
</div>
