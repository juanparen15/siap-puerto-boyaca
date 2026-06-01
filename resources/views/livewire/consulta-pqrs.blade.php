<div class="max-w-3xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-8 text-center animate-on-scroll">
        <div class="flex justify-center mb-3">
            <lord-icon src="https://cdn.lordicon.com/iuvnsegf.json"
                trigger="loop" delay="800" stroke="bold"
                colors="primary:#1B6B2F"
                style="width:56px;height:56px"></lord-icon>
        </div>
        <h1 class="text-2xl font-bold text-[#1B6B2F]">Consultar PQRS</h1>
        <p class="text-gray-500 text-sm mt-1">Consulte el estado de su Petición, Queja, Reclamo o Solicitud</p>
    </div>

    {{-- Search form --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-5">Búsqueda</h2>

        {{-- Type toggle --}}
        <div class="flex gap-3 mb-5">
            <button
                type="button"
                wire:click="$set('tipoBusqueda', 'radicado')"
                class="flex-1 py-2 px-4 rounded-lg text-sm font-medium border transition-colors
                    {{ $tipoBusqueda === 'radicado'
                        ? 'bg-[#1B6B2F] text-white border-[#1B6B2F]'
                        : 'bg-white text-gray-600 border-gray-300 hover:border-[#1B6B2F] hover:text-[#1B6B2F]' }}">
                Por número de radicado
            </button>
            <button
                type="button"
                wire:click="$set('tipoBusqueda', 'cedula')"
                class="flex-1 py-2 px-4 rounded-lg text-sm font-medium border transition-colors
                    {{ $tipoBusqueda === 'cedula'
                        ? 'bg-[#1B6B2F] text-white border-[#1B6B2F]'
                        : 'bg-white text-gray-600 border-gray-300 hover:border-[#1B6B2F] hover:text-[#1B6B2F]' }}">
                Por número de cédula
            </button>
        </div>

        {{-- Search input --}}
        <div class="flex gap-3">
            <div class="flex-1">
                <input
                    type="text"
                    wire:model="busqueda"
                    wire:keydown.enter="consultar"
                    placeholder="{{ $tipoBusqueda === 'radicado' ? 'Ej. PQRS-2026-000001' : 'Ej. 12345678' }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent">
            </div>
            <button
                type="button"
                wire:click="consultar"
                wire:loading.attr="disabled"
                class="bg-[#1B6B2F] hover:bg-[#155a26] text-white font-medium text-sm px-5 py-2 rounded-lg transition-colors disabled:opacity-60">
                <span wire:loading.remove wire:target="consultar">Consultar</span>
                <span wire:loading wire:target="consultar">Buscando…</span>
            </button>
        </div>
    </div>

    {{-- Error message --}}
    @if ($error)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-red-700 text-sm">
            {{ $error }}
        </div>
    @endif

    {{-- Result --}}
    @if ($pqrs)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8 space-y-6">

            {{-- Radicado and estado --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Número de radicado</p>
                    <p class="text-xl font-bold text-[#1B6B2F] font-mono">{{ $pqrs->radicado }}</p>
                </div>
                <div>
                    @php
                        $estadoClasses = match($pqrs->estado) {
                            'radicada'   => 'bg-amber-100 text-amber-800 border-amber-200',
                            'en_proceso' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'respondida' => 'bg-green-100 text-green-800 border-green-200',
                            'cerrada'    => 'bg-gray-100 text-gray-700 border-gray-200',
                            default      => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                        $estadoLabel = match($pqrs->estado) {
                            'radicada'   => 'Radicada',
                            'en_proceso' => 'En proceso',
                            'respondida' => 'Respondida',
                            'cerrada'    => 'Cerrada',
                            default      => ucfirst($pqrs->estado),
                        };
                    @endphp
                    <span class="inline-block text-sm font-semibold px-4 py-1.5 rounded-full border {{ $estadoClasses }}">
                        {{ $estadoLabel }}
                    </span>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Details --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tipo de solicitud</p>
                    <p class="text-sm font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $pqrs->tipo_solicitud) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Fecha de radicación</p>
                    <p class="text-sm font-medium text-gray-800">{{ $pqrs->created_at->format('d/m/Y H:i') }}</p>
                </div>
                @if ($pqrs->elemento)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Elemento asociado</p>
                        <p class="text-sm font-medium text-gray-800">
                            {{ ucfirst($pqrs->elemento->tipo) }}
                            @if ($pqrs->elemento->clasificacion)
                                — {{ str_replace('_', ' ', $pqrs->elemento->clasificacion) }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            {{-- Acción tomada --}}
            @if ($pqrs->accion_tomada)
                <div class="bg-green-50 rounded-xl border border-green-200 p-4">
                    <p class="text-xs text-green-700 uppercase tracking-wide font-semibold mb-1">Acción tomada</p>
                    <p class="text-sm text-green-900">{{ $pqrs->accion_tomada }}</p>
                </div>
            @endif

            {{-- Map --}}
            @if ($pqrs->latitud && $pqrs->longitud)
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Ubicación del reporte</p>
                    <div id="pqrs-map" style="height: 200px;"
                         data-lat="{{ $pqrs->latitud }}"
                         data-lng="{{ $pqrs->longitud }}"
                         class="w-full rounded-xl border border-gray-200 z-0"
                         wire:ignore></div>
                </div>
            @endif

            @push('styles')
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
            @endpush

            @push('scripts')
                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV/XN/sp38=" crossorigin=""></script>
                <script>
                    document.addEventListener('livewire:updated', function () {
                        var el = document.getElementById('pqrs-map');
                        if (el && !el._leafletMap) {
                            var lat = parseFloat(el.dataset.lat);
                            var lng = parseFloat(el.dataset.lng);
                            el._leafletMap = L.map(el).setView([lat, lng], 16);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                                maxZoom: 19
                            }).addTo(el._leafletMap);
                            L.marker([lat, lng]).addTo(el._leafletMap);
                        }
                    });
                </script>
            @endpush

            {{-- History timeline --}}
            @if ($pqrs->historial->isNotEmpty())
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <lord-icon src="https://cdn.lordicon.com/laobovmg.json"
                            trigger="loop" delay="1500" stroke="bold"
                            colors="primary:#6b7280"
                            style="width:20px;height:20px"></lord-icon>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Historial de estados</p>
                    </div>
                    <ol class="relative border-l border-gray-200 ml-3 space-y-6">
                        @foreach ($pqrs->historial as $item)
                            <li class="ml-6">
                                <span class="absolute -left-2.5 flex items-center justify-center w-5 h-5 bg-[#1B6B2F] rounded-full ring-4 ring-white">
                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="6"/>
                                    </svg>
                                </span>
                                <div class="bg-gray-50 rounded-lg border border-gray-100 p-3">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        @if ($item->estado_anterior)
                                            <span class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $item->estado_anterior) }}</span>
                                            <span class="text-gray-400 text-xs">→</span>
                                        @endif
                                        <span class="text-xs font-semibold text-[#1B6B2F] capitalize">{{ str_replace('_', ' ', $item->estado_nuevo) }}</span>
                                        @if ($item->usuario)
                                            <span class="text-xs text-gray-400">por {{ $item->usuario->name }}</span>
                                        @endif
                                    </div>
                                    @if ($item->observacion)
                                        <p class="text-sm text-gray-700">{{ $item->observacion }}</p>
                                    @endif
                                    @if ($item->created_at)
                                        <p class="text-xs text-gray-400 mt-1">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif

        </div>
    @endif

</div>
