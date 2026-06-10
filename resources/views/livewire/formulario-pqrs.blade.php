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
                                        colors="primary:#121331,secondary:#000000" style="width:34px;height:34px;{{ $paso >= $n ? '' : 'opacity:.4;' }}"></lord-icon>
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
                                    <select wire:model="tipo_solicitud" class="siap-select" style="width:100%;height:auto;min-height:44px;">
                                        <option value="">-- Seleccione --</option>
                                        <option value="peticion">Petición</option>
                                        <option value="queja">Queja</option>
                                        <option value="reclamo">Reclamo</option>
                                        <option value="solicitud">Solicitud</option>
                                    </select>
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
                                    <a href="{{ route('pqrs.consultar') }}" class="siap-btn">Consultar estado de mi PQRS</a>
                                    <a href="{{ route('pqrs') }}" class="siap-btn siap-btn-ghost">Radicar otra PQRS</a>
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
@endpush
