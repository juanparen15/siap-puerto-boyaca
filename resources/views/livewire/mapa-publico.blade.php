<div>
    {{-- Header --}}
    <section class="relative overflow-hidden">
        <div class="corp-dotgrid pointer-events-none absolute inset-0 opacity-50"></div>
        <div class="relative z-10 mx-auto max-w-6xl px-4 pt-28 pb-6 lg:pt-32">
            <span class="corp-eyebrow">Inventario georeferenciado</span>
            <h1 class="font-display mt-4 text-4xl font-bold tracking-tight md:text-5xl" style="color:var(--siap-ink)">
                Mapa del alumbrado público
            </h1>
            <p class="mt-2 max-w-2xl text-slate-500">
                Explora los puntos de luz del municipio. Filtra por tipo, estado o zona, y toca un punto para ver su detalle.
            </p>
        </div>
    </section>

    {{-- Map + filtros --}}
    <section class="pb-16">
        <div class="mx-auto max-w-6xl px-4">
            <div class="corp-card relative overflow-hidden p-2">

                {{-- Toolbar de filtros (vidrio) --}}
                <div class="lg-surface lg-sheen absolute inset-x-3 top-3 z-[5] flex flex-wrap items-center gap-2 rounded-xl px-3 py-2">
                    <select wire:model.live="filtroTipo" class="lg-input rounded-lg px-3 py-2 text-sm text-slate-700">
                        <option value="">Todos los tipos</option>
                        <option value="luminaria">Luminaria</option>
                        <option value="poste">Poste</option>
                        <option value="reflector">Reflector</option>
                        <option value="sendero_peatonal">Sendero Peatonal</option>
                        <option value="campo_deportivo">Campo Deportivo</option>
                        <option value="luminaria_parque">Luminaria de Parque</option>
                    </select>
                    <select wire:model.live="filtroEstado" class="lg-input rounded-lg px-3 py-2 text-sm text-slate-700">
                        <option value="">Todos los estados</option>
                        <option value="operativa">Operativa</option>
                        <option value="no_operativa">No Operativa</option>
                        <option value="desinstalada">Desinstalada</option>
                    </select>
                    <select wire:model.live="filtroClasificacion" class="lg-input rounded-lg px-3 py-2 text-sm text-slate-700">
                        <option value="">Todas las zonas</option>
                        <option value="casco_urbano">Casco Urbano</option>
                        <option value="puerto_serviez">Puerto Serviez</option>
                    </select>
                    <span class="ml-auto hidden items-center gap-1 self-center pr-1 text-sm text-slate-500 sm:flex">
                        <span id="contador-elementos" class="font-semibold text-[#3366CC]">—</span> visibles
                    </span>
                </div>

                <div id="mapa-publico"
                     wire:ignore
                     x-data="mapaPublico()"
                     x-init="init()"
                     @filtros-changed.window="actualizarFiltros($event.detail)"
                     class="overflow-hidden rounded-[1rem]"
                     style="height:76vh;min-height:480px;width:100%;">
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
@vite(['resources/js/mapa-publico.js'])
@endpush
