<div>
    {{-- Filters bar --}}
    <div class="flex flex-wrap gap-3 px-4 py-3 bg-white shadow-sm border-b">
        <select wire:model.live="filtroTipo"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent">
            <option value="">Todos los tipos</option>
            <option value="luminaria">Luminaria</option>
            <option value="poste">Poste</option>
            <option value="reflector">Reflector</option>
            <option value="sendero_peatonal">Sendero Peatonal</option>
            <option value="campo_deportivo">Campo Deportivo</option>
            <option value="luminaria_parque">Luminaria de Parque</option>
        </select>

        <select wire:model.live="filtroEstado"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent">
            <option value="">Todos los estados</option>
            <option value="operativa">Operativa</option>
            <option value="no_operativa">No Operativa</option>
            <option value="desinstalada">Desinstalada</option>
        </select>

        <select wire:model.live="filtroClasificacion"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent">
            <option value="">Todas las zonas</option>
            <option value="casco_urbano">Casco Urbano</option>
            <option value="puerto_serviez">Puerto Serviez</option>
        </select>

        <span class="ml-auto text-sm text-gray-500 self-center">
            <span id="contador-elementos">—</span> elementos visibles
        </span>
    </div>

    {{-- Map container --}}
    <div id="mapa-publico"
         style="height: calc(100vh - 130px);"
         wire:ignore
         x-data="mapaPublico()"
         x-init="init()"
         @filtros-changed.window="actualizarFiltros($event.detail)">
    </div>
</div>

@push('scripts')
@vite(['resources/js/mapa-publico.js'])
@endpush
