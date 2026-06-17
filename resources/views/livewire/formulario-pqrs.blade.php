<div>
    {{-- Encabezado --}}
    <section class="page-title-area">
        <div class="container large">
            <div class="page-title-area-inner section-spacing-top">
                <div class="page-title-wrapper">
                    <h2 class="page-title fade-anim">PQRS</h2>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container large">
            <div class="section-header fade-anim" style="padding-top:24px;text-align:center;">
                <div class="section-title-wrapper">
                    <div class="subtitle-wrapper"><span class="section-subtitle">Trámite ciudadano</span></div>
                    <div class="title-wrapper">
                        <h2 class="section-title font-instrumentsans-medium">Radicar PQRS</h2>
                    </div>
                </div>
                <div class="text-wrapper">
                    <p class="text">Peticiones, Quejas, Reclamos y Solicitudes — Alumbrado Público.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-spacing-bottom">
        <div class="container large">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- Indicador de pasos --}}
                    <div style="display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:34px;">
                        @foreach ([['knzzcfyy','Datos',1],['hmpomorl','Solicitud',2],['lvrxlmju','Confirmación',3]] as $idx => [$ic,$lbl,$n])
                            <div style="display:flex;flex-direction:column;align-items:center;">
                                <div style="width:56px;height:56px;border-radius:9999px;display:flex;align-items:center;justify-content:center;border:2px solid;{{ $paso >= $n ? 'border-color:#3366CC;background:rgba(51,102,204,.08);' : 'border-color:#e2e8f0;background:#fff;' }}">
                                    <lord-icon src="https://cdn.lordicon.com/{{ $ic }}.json" trigger="loop" delay="{{ 1200 + $idx*400 }}"
                                        colors="primary:#3366CC,secondary:#22c55e" style="width:34px;height:34px;{{ $paso >= $n ? '' : 'opacity:.4;' }}"></lord-icon>
                                </div>
                                <span style="font-size:12px;margin-top:6px;{{ $paso >= $n ? 'color:#3366CC;font-weight:600;' : 'color:#94a3b8;' }}">{{ $lbl }}</span>
                            </div>
                            @if ($n < 3)
                                <div style="width:48px;height:2px;margin-bottom:18px;{{ $paso > $n ? 'background:#3366CC;' : 'background:#e2e8f0;' }}"></div>
                            @endif
                        @endforeach
                    </div>

                    <div class="siap-panel fade-anim" style="padding:40px;">

                        {{-- PASO 1 --}}
                        @if ($paso === 1)
                            <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:28px;color:var(--siap-ink);margin:0 0 18px;">Datos del ciudadano</h3>

                            {{-- Interruptor: radicar de forma anónima --}}
                            <label style="display:flex;align-items:flex-start;gap:12px;background:#f8fafc;border:1px solid rgba(12,42,67,.1);border-radius:12px;padding:14px 16px;margin-bottom:22px;cursor:pointer;">
                                <input type="checkbox" wire:model.live="anonimo" style="width:18px;height:18px;margin-top:2px;accent-color:#3366CC;cursor:pointer;flex-shrink:0;">
                                <span>
                                    <span style="display:block;font-weight:600;color:var(--siap-ink);font-size:14px;">Radicar de forma anónima</span>
                                    <span style="display:block;font-size:12px;color:#64748b;margin-top:2px;">No necesitas dar tu nombre ni cédula. Puedes dejar un correo o teléfono si quieres recibir el avance.</span>
                                </span>
                            </label>

                            <div class="row" style="--bs-gutter-y:18px;">
                                @unless ($anonimo)
                                    <div class="col-12">
                                        <label class="siap-label">Nombre completo <span style="color:#dc2626;">*</span></label>
                                        <input type="text" wire:model="nombre_ciudadano" placeholder="Ej. Juan Carlos Pérez" class="siap-input">
                                        @error('nombre_ciudadano') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="siap-label">Número de cédula <span style="color:#dc2626;">*</span></label>
                                        <input type="text" wire:model="numero_cedula" inputmode="numeric" placeholder="Ej. 12345678" class="siap-input">
                                        @error('numero_cedula') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                    </div>
                                @endunless
                                <div class="col-md-6">
                                    <label class="siap-label">Correo electrónico <span style="color:#94a3b8;font-weight:400;">(opcional)</span></label>
                                    <input type="email" wire:model="email" placeholder="correo@ejemplo.com" class="siap-input">
                                    @error('email') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="siap-label">Teléfono celular <span style="color:#94a3b8;font-weight:400;">(opcional)</span></label>
                                    <input type="tel" wire:model="telefono" inputmode="numeric" placeholder="Ej. 3001234567" class="siap-input">
                                    @error('telefono') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div style="margin-top:28px;display:flex;justify-content:flex-end;">
                                <button wire:click="siguiente" class="siap-btn">Siguiente</button>
                            </div>
                        @endif

                        {{-- PASO 2 --}}
                        @if ($paso === 2)
                            <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:28px;color:var(--siap-ink);margin:0 0 24px;">Detalles de la solicitud</h3>
                            <div class="row" style="--bs-gutter-y:18px;">
                                <div class="col-12">
                                    <label class="siap-label">Tipo de solicitud <span style="color:#dc2626;">*</span></label>
                                    <select wire:model.live="tipo_solicitud" class="siap-select" style="width:100%;height:auto;min-height:44px;">
                                        <option value="">-- Seleccione --</option>
                                        @foreach (\App\Enums\TipoSolicitud::cases() as $tipo)
                                            <option value="{{ $tipo->value }}">{{ $tipo->label() }}</option>
                                        @endforeach
                                    </select>
                                    @if ($tipo_solicitud && ($caso = \App\Enums\TipoSolicitud::tryFrom($tipo_solicitud)))
                                        <p style="font-size:12px;color:#64748b;margin:6px 0 0;">{{ $caso->descripcion() }}@if ($caso->diasHabiles()) · Plazo de respuesta: {{ $caso->diasHabiles() }} días hábiles.@endif</p>
                                    @endif
                                    @error('tipo_solicitud') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="siap-label">Descripción <span style="color:#dc2626;">*</span> <span style="color:#94a3b8;font-weight:400;">(mínimo 20 caracteres)</span></label>
                                    <textarea wire:model="descripcion" rows="5" placeholder="Describa detalladamente su solicitud, incluyendo la ubicación del problema y el tiempo que lleva presentándose..." class="siap-input" style="resize:none;"></textarea>
                                    <p style="font-size:12px;color:#94a3b8;margin:6px 0 0;text-align:right;">{{ mb_strlen($descripcion) }} / 2000</p>
                                    @error('descripcion') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="siap-label">Ubicación del problema <span style="color:#94a3b8;font-weight:400;">— toca un punto del mapa para seleccionarlo</span></label>

                                    @if ($elemento_id)
                                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;background:rgba(51,102,204,.06);border:1px solid rgba(51,102,204,.2);border-radius:10px;padding:8px 12px;margin:0 0 10px;">
                                            <span style="font-size:13px;color:var(--siap-blue);font-weight:600;">Punto seleccionado: #{{ $elemento_id }}</span>
                                            <button type="button" wire:click="limpiarPunto" style="background:none;border:0;color:#dc2626;font-size:12px;font-weight:600;cursor:pointer;">Quitar selección</button>
                                        </div>
                                    @endif

                                    <div style="display:flex;justify-content:flex-end;margin-bottom:10px;">
                                        <button type="button" class="siap-btn-ubic" onclick="window.siapMiUbicacion && window.siapMiUbicacion()">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/><line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/></svg>
                                            Mi ubicación
                                        </button>
                                    </div>

                                    <div class="siap-map-shell">
                                        <div class="siap-map-float" style="bottom:14px;left:14px;padding:10px 12px;">
                                            <p style="margin:0 0 6px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#64748b;">Estado</p>
                                            @foreach ([['#16a34a','Sin reportes'],['#ca8a04','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                                                <div class="siap-legend-row" style="margin-top:4px;"><span class="siap-dot" style="background:{{ $c }}"></span>{{ $l }}</div>
                                            @endforeach
                                        </div>
                                        <div id="mapa-pqrs" class="siap-map-canvas" style="height:380px;"
                                             wire:ignore
                                             x-data="mapaPqrs({ lat: {{ $latitud ?? 'null' }}, lng: {{ $longitud ?? 'null' }}, hasPreciseLocation: {{ ($latitud !== null) ? 'true' : 'false' }}, elementoId: {{ $elemento_id ?? 'null' }} })"
                                             x-init="init()"></div>
                                    </div>

                                    @if ($latitud !== null && $longitud !== null)
                                        <p style="font-size:12px;color:#64748b;margin:8px 0 0;">Ubicación: {{ number_format((float) $latitud, 6) }}, {{ number_format((float) $longitud, 6) }}</p>
                                    @else
                                        <p style="font-size:12px;color:#94a3b8;margin:8px 0 0;">Aún no seleccionas un punto. Es opcional, pero ayuda a ubicar el problema.</p>
                                    @endif
                                </div>

                                {{-- Evidencia opcional (hasta 3 fotos) --}}
                                <div class="col-12">
                                    <label class="siap-label">Evidencia <span style="color:#94a3b8;font-weight:400;">(opcional — hasta 3 fotos)</span></label>
                                    <label for="fotos-pqrs" style="display:flex;align-items:center;gap:12px;border:1.5px dashed rgba(12,42,67,.25);border-radius:12px;padding:14px 16px;cursor:pointer;color:#475569;font-size:14px;background:#f8fafc;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#3366CC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px;flex-shrink:0;"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        <span>Toca para adjuntar fotos del problema <span style="color:#94a3b8;">(JPG/PNG, máx. 4 MB c/u)</span></span>
                                    </label>
                                    <input id="fotos-pqrs" type="file" wire:model="fotos" multiple accept="image/*" style="display:none;">
                                    <div wire:loading wire:target="fotos" style="font-size:12px;color:#3366CC;margin-top:6px;">Subiendo fotos…</div>
                                    @error('fotos') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror
                                    @error('fotos.*') <p style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</p> @enderror

                                    @if (count($fotos))
                                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:12px;">
                                            @foreach ($fotos as $i => $foto)
                                                <div style="position:relative;width:88px;height:88px;border-radius:10px;overflow:hidden;border:1px solid rgba(12,42,67,.12);">
                                                    @if (is_object($foto) && method_exists($foto, 'temporaryUrl'))
                                                        <img src="{{ $foto->temporaryUrl() }}" style="width:100%;height:100%;object-fit:cover;display:block;">
                                                    @endif
                                                    <button type="button" wire:click="quitarFoto({{ $i }})" title="Quitar"
                                                            style="position:absolute;top:3px;right:3px;background:rgba(220,38,38,.92);color:#fff;border:0;border-radius:9999px;width:20px;height:20px;font-size:13px;line-height:1;cursor:pointer;">&times;</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @error('general') <p style="color:#dc2626;font-size:14px;margin-top:16px;text-align:center;">{{ $message }}</p> @enderror
                            <div style="margin-top:28px;display:flex;justify-content:space-between;gap:12px;">
                                <button wire:click="anterior" class="siap-btn siap-btn-ghost">Anterior</button>
                                <button wire:click="enviar" class="siap-btn">Enviar solicitud</button>
                            </div>
                        @endif

                        {{-- PASO 3 --}}
                        @if ($paso === 3)
                            <div style="text-align:center;padding:8px 0;">
                                <div style="display:flex;justify-content:center;margin-bottom:14px;">
                                    <lord-icon src="https://cdn.lordicon.com/lvrxlmju.json" trigger="loop" delay="500"
                                        style="width:110px;height:110px"></lord-icon>
                                </div>
                                <h3 style="font-family:'Thunder',sans-serif;font-weight:600;font-size:32px;color:var(--siap-ink);margin:0 0 6px;">PQRS radicada exitosamente</h3>
                                <p style="color:#475569;font-size:14px;margin:0 0 22px;">Su solicitud ha sido registrada en el sistema.</p>

                                <div style="background:#f0fdf4;border:1px solid rgba(51,102,204,.4);border-radius:18px;padding:18px 28px;display:inline-block;margin-bottom:24px;">
                                    <p style="font-size:13px;color:#64748b;margin:0 0 4px;">Número de radicado</p>
                                    <p class="siap-stat-num" style="font-size:28px;color:var(--siap-ink);margin:0;letter-spacing:1px;">{{ $radicadoGenerado }}</p>
                                </div>

                                @if ($fechaLimiteTexto)
                                    <p style="color:#475569;font-size:14px;margin:0 0 22px;">
                                        Tu <strong>{{ $tipoLabel }}</strong> será atendida a más tardar el
                                        <strong style="color:var(--siap-blue);">{{ $fechaLimiteTexto }}</strong>
                                        ({{ $plazoDias }} días hábiles).
                                    </p>
                                @endif

                                <div style="text-align:left;background:#f8fafc;border-radius:16px;padding:22px;margin-bottom:24px;">
                                    <p style="font-weight:700;color:var(--siap-ink);font-size:14px;margin:0 0 14px;">Próximos pasos:</p>
                                    @foreach ([
                                        'Guarde su número de radicado. Lo necesitará para consultar el estado de su solicitud.',
                                        'La Secretaría de Obras Públicas revisará su solicitud en un plazo de 15 días hábiles.',
                                        'Si proporcionó correo electrónico o teléfono, recibirá notificaciones sobre el avance.',
                                    ] as $i => $paso_txt)
                                        <div style="display:flex;align-items:flex-start;gap:12px;font-size:14px;color:#475569;margin-bottom:10px;">
                                            <span style="width:22px;height:22px;background:var(--siap-blue);color:#fff;border-radius:9999px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px;font-weight:700;">{{ $i + 1 }}</span>
                                            <p style="margin:0;">{{ $paso_txt }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                                    <button type="button" onclick="window.descargarComprobante && window.descargarComprobante()" class="siap-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Descargar comprobante
                                    </button>
                                    <a href="{{ route('pqrs.consultar') }}" class="siap-btn siap-btn-ghost">Consultar estado</a>
                                    <a href="{{ route('pqrs') }}" class="siap-btn siap-btn-ghost">Radicar otra</a>
                                </div>
                            </div>

                            {{-- Comprobante (fuera de pantalla) — identidad del portal: Thunder,
                                 eyebrow con línea, degradado gov.co y banda navy como el footer. --}}
                            <div id="comprobante-pqrs" data-radicado="{{ $radicadoGenerado }}"
                                 style="position:fixed;left:-10000px;top:0;width:680px;background:#ffffff;color:#0c2a43;font-family:'Sequel Sans Roman Body',Arial,sans-serif;border-radius:24px;overflow:hidden;border:1px solid #e6eaf2;box-sizing:border-box;">

                                <div style="height:8px;background:linear-gradient(90deg,#3366CC 0%,#6c8cff 55%,#8a7bff 100%);"></div>

                                <div style="padding:42px 46px 32px;">
                                    {{-- Encabezado --}}
                                    <div style="display:flex;align-items:center;gap:18px;margin-bottom:30px;">
                                        <img src="{{ asset('images/LOGO ALCALDIA.png') }}" style="height:84px;width:auto;" crossorigin="anonymous">
                                        <div style="flex:1;">
                                            <div style="font-family:'Thunder',sans-serif;font-weight:700;font-size:34px;line-height:.9;color:#0c2a43;letter-spacing:.5px;text-transform:uppercase;">SIAP</div>
                                            <div style="font-size:13px;color:#475569;margin-top:3px;">Alcaldía de Puerto Boyacá · Alumbrado Público</div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-size:11px;color:#64748b;letter-spacing:1px;text-transform:uppercase;">Generado</div>
                                            <div style="font-size:13px;font-weight:700;color:#0c2a43;margin-top:2px;">{{ now()->format('d/m/Y · H:i') }}</div>
                                        </div>
                                    </div>

                                    {{-- Eyebrow + título grande (estilo hero) --}}
                                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                                        <span style="display:inline-block;width:32px;height:2px;background:#3366CC;"></span>
                                        <span style="font-size:12px;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#3366CC;">Comprobante de radicación</span>
                                    </div>
                                    <div style="font-family:'Thunder',sans-serif;font-weight:700;font-size:48px;line-height:.92;color:#0c2a43;text-transform:uppercase;margin-bottom:26px;">
                                        PQRS Radicada
                                    </div>

                                    {{-- Radicado destacado --}}
                                    <div style="background:#f1f3f7;border-radius:16px;padding:20px 26px;margin-bottom:26px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
                                        <div>
                                            <div style="font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#3366CC;margin-bottom:4px;">Número de radicado</div>
                                            <div style="font-family:'Thunder',sans-serif;font-weight:700;font-size:42px;line-height:1;color:#0c2a43;letter-spacing:1px;">{{ $radicadoGenerado }}</div>
                                        </div>
                                        <span style="background:#dbeafe;color:#1e40af;font-size:13px;font-weight:700;padding:7px 16px;border-radius:9999px;white-space:nowrap;">Radicada</span>
                                    </div>

                                    {{-- Detalle --}}
                                    <table style="width:100%;border-collapse:collapse;font-size:14px;color:#0c2a43;">
                                        <tr><td style="padding:12px 0;color:#64748b;width:44%;border-bottom:1px solid #eef1f6;">Tipo de solicitud</td><td style="padding:12px 0;font-weight:700;border-bottom:1px solid #eef1f6;text-align:right;">{{ \App\Enums\TipoSolicitud::tryFrom($tipo_solicitud)?->label() ?? $tipo_solicitud }}</td></tr>
                                        <tr><td style="padding:12px 0;color:#64748b;border-bottom:1px solid #eef1f6;">Solicitante</td><td style="padding:12px 0;font-weight:700;border-bottom:1px solid #eef1f6;text-align:right;">{{ $anonimo ? 'Ciudadano anónimo' : $nombre_ciudadano }}</td></tr>
                                        @unless ($anonimo)
                                            <tr><td style="padding:12px 0;color:#64748b;border-bottom:1px solid #eef1f6;">Cédula</td><td style="padding:12px 0;font-weight:700;border-bottom:1px solid #eef1f6;text-align:right;">{{ $numero_cedula }}</td></tr>
                                        @endunless
                                        @if ($elemento_id)
                                            <tr><td style="padding:12px 0;color:#64748b;border-bottom:1px solid #eef1f6;">Punto reportado</td><td style="padding:12px 0;font-weight:700;border-bottom:1px solid #eef1f6;text-align:right;">#{{ $elemento_id }}</td></tr>
                                        @endif
                                        @if ($fechaLimiteTexto)
                                            <tr><td style="padding:12px 0;color:#64748b;">Fecha límite de respuesta</td><td style="padding:12px 0;font-weight:700;color:#3366CC;text-align:right;">{{ $fechaLimiteTexto }}</td></tr>
                                        @endif
                                    </table>
                                </div>

                                {{-- Banda navy (como el footer del portal) --}}
                                <div style="background:#0c2a43;padding:18px 46px;display:flex;align-items:center;justify-content:space-between;gap:14px;">
                                    <span style="font-family:'Thunder',sans-serif;font-weight:600;font-size:19px;letter-spacing:1px;text-transform:uppercase;color:#ffffff;">SIAP · Puerto Boyacá</span>
                                    <span style="font-size:11px;color:#9fb3c8;text-align:right;line-height:1.6;">Conserve este comprobante · consúltelo en el portal SIAP<br>Desarrollado por el contrato 099 de 2026</span>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
@vite(['resources/js/pqrs-pin-map.js'])
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    window.descargarComprobante = function () {
        var el = document.getElementById('comprobante-pqrs');
        if (!el) return;
        if (!window.html2canvas) { alert('Aún se está cargando el generador. Intenta de nuevo en un momento.'); return; }
        window.html2canvas(el, { scale: 2, backgroundColor: '#ffffff', useCORS: true, logging: false }).then(function (canvas) {
            var a = document.createElement('a');
            a.download = 'comprobante-' + (el.dataset.radicado || 'pqrs') + '.png';
            a.href = canvas.toDataURL('image/png');
            a.click();
        }).catch(function (e) {
            console.error(e);
            alert('No se pudo generar el comprobante. Intenta de nuevo.');
        });
    };
</script>
@endpush
