<div>
    {{-- Encabezado --}}
    <section class="page-title-area">
        <div class="container large">
            <div class="page-title-area-inner section-spacing-top">
                <div class="page-title-wrapper">
                    <h2 class="page-title fade-anim">Mapa</h2>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container large">
            <div class="section-header fade-anim" style="padding-top:24px;">
                <div class="section-title-wrapper">
                    <div class="subtitle-wrapper"><span class="section-subtitle">Inventario georreferenciado</span></div>
                    <div class="title-wrapper">
                        <h2 class="section-title font-instrumentsans-medium">Mapa del alumbrado público</h2>
                    </div>
                </div>
                <div class="text-wrapper">
                    <p class="text">Explora los puntos de luz del municipio. Filtra por tipo, estado o zona, y toca un punto para ver su detalle.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Mapa + filtros --}}
    <section class="section-spacing-bottom">
        <div class="container large">

            {{-- Barra de filtros (arriba del mapa, consistente y responsive) --}}
            <div class="siap-filterbar fade-anim">
                <div class="siap-filter-group">
                    <select wire:model.live="filtroTipo" class="siap-select" aria-label="Filtrar por tipo">
                        <option value="">Todos los tipos</option>
                        <option value="luminaria">Luminaria</option>
                        <option value="poste">Poste</option>
                        <option value="reflector">Reflector</option>
                        <option value="sendero_peatonal">Sendero Peatonal</option>
                        <option value="campo_deportivo">Campo Deportivo</option>
                        <option value="luminaria_parque">Luminaria de Parque</option>
                    </select>
                    <select wire:model.live="filtroEstado" class="siap-select" aria-label="Filtrar por estado">
                        <option value="">Todos los estados</option>
                        <option value="operativa">Operativa</option>
                        <option value="no_operativa">No Operativa</option>
                        <option value="desinstalada">Desinstalada</option>
                    </select>
                    <select wire:model.live="filtroClasificacion" class="siap-select" aria-label="Filtrar por zona">
                        <option value="">Todas las zonas</option>
                        <option value="casco_urbano">Casco Urbano</option>
                        <option value="puerto_serviez">Puerto Serviez</option>
                    </select>
                </div>
                <div class="siap-filter-actions">
                    <span class="siap-count"><strong id="contador-elementos">—</strong> visibles</span>
                    <button type="button" class="siap-btn-ubic" onclick="window.siapMiUbicacion && window.siapMiUbicacion()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="5"/><line x1="12" y1="19" x2="12" y2="22"/><line x1="2" y1="12" x2="5" y2="12"/><line x1="19" y1="12" x2="22" y2="12"/></svg>
                        Mi ubicación
                    </button>
                </div>
            </div>

            {{-- Mapa --}}
            <div class="siap-map-shell fade-anim" data-delay="0.2">
                <div class="siap-map-float" style="bottom:18px;left:18px;padding:12px 16px;">
                    <p style="margin:0 0 8px;font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#64748b;">Estado</p>
                    @foreach ([['#16a34a','Sin reportes'],['#ca8a04','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                        <div class="siap-legend-row" style="margin-top:4px;"><span class="siap-dot" style="background:{{ $c }}"></span>{{ $l }}</div>
                    @endforeach
                </div>
                <div id="mapa-publico"
                     wire:ignore
                     x-data="mapaPublico()"
                     x-init="init()"
                     @filtros-changed.window="actualizarFiltros($event.detail)"
                     class="siap-map-canvas"
                     style="height:70vh;min-height:420px;">
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
@vite(['resources/js/mapa-publico.js'])
@endpush
