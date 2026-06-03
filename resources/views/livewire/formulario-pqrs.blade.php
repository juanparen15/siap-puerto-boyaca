<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-8 text-center animate-on-scroll">
        <div class="flex justify-center mb-3">
            <lord-icon src="https://cdn.lordicon.com/vwzukuhn.json"
                trigger="loop" delay="1000" stroke="bold"
                colors="primary:#1B6B2F"
                style="width:56px;height:56px"></lord-icon>
        </div>
        <h1 class="text-2xl font-bold text-[#1B6B2F]">Radicar PQRS</h1>
        <p class="text-gray-500 text-sm mt-1">Peticiones, Quejas, Reclamos y Solicitudes — Alumbrado Público</p>
    </div>

    {{-- Step indicator --}}
    <div class="flex items-center justify-center mb-8 gap-2">

        {{-- Step 1 --}}
        <div class="flex items-center gap-2">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border-2 transition-colors
                    {{ $paso >= 1 ? 'border-[#1B6B2F] bg-green-50' : 'border-gray-200 bg-white' }}">
                    <lord-icon src="https://cdn.lordicon.com/gubjuhss.json"
                        trigger="loop" delay="1200" stroke="bold"
                        colors="primary:{{ $paso >= 1 ? '#1B6B2F' : '#9ca3af' }}"
                        style="width:36px;height:36px"></lord-icon>
                </div>
                <span class="text-xs mt-1 {{ $paso >= 1 ? 'text-[#1B6B2F] font-semibold' : 'text-gray-400' }}">Datos</span>
            </div>
            <div class="w-12 h-0.5 mb-4 transition-colors {{ $paso > 1 ? 'bg-[#1B6B2F]' : 'bg-gray-200' }}"></div>
        </div>

        {{-- Step 2 --}}
        <div class="flex items-center gap-2">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center border-2 transition-colors
                    {{ $paso >= 2 ? 'border-[#1B6B2F] bg-green-50' : 'border-gray-200 bg-white' }}">
                    <lord-icon src="https://cdn.lordicon.com/tbabdzcy.json"
                        trigger="loop" delay="1600" stroke="bold"
                        colors="primary:{{ $paso >= 2 ? '#1B6B2F' : '#9ca3af' }}"
                        style="width:36px;height:36px"></lord-icon>
                </div>
                <span class="text-xs mt-1 {{ $paso >= 2 ? 'text-[#1B6B2F] font-semibold' : 'text-gray-400' }}">Solicitud</span>
            </div>
            <div class="w-12 h-0.5 mb-4 transition-colors {{ $paso > 2 ? 'bg-[#1B6B2F]' : 'bg-gray-200' }}"></div>
        </div>

        {{-- Step 3 --}}
        <div class="flex flex-col items-center">
            <div class="w-14 h-14 rounded-full flex items-center justify-center border-2 transition-colors
                {{ $paso >= 3 ? 'border-[#1B6B2F] bg-green-50' : 'border-gray-200 bg-white' }}">
                <lord-icon src="https://cdn.lordicon.com/pxixoqxa.json"
                    trigger="loop" delay="2000" stroke="bold"
                    colors="primary:{{ $paso >= 3 ? '#1B6B2F' : '#9ca3af' }}"
                    style="width:36px;height:36px"></lord-icon>
            </div>
            <span class="text-xs mt-1 {{ $paso >= 3 ? 'text-[#1B6B2F] font-semibold' : 'text-gray-400' }}">Confirmación</span>
        </div>

    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 md:p-8">

        {{-- ─── STEP 1: Citizen data ─── --}}
        @if ($paso === 1)
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Datos del ciudadano</h2>

            <div class="space-y-5">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="nombre_ciudadano"
                           placeholder="Ej. Juan Carlos Pérez"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('nombre_ciudadano') border-red-400 @enderror">
                    @error('nombre_ciudadano')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cédula --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Número de cédula <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="numero_cedula"
                           placeholder="Ej. 12345678"
                           inputmode="numeric"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('numero_cedula') border-red-400 @enderror">
                    @error('numero_cedula')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <input type="email"
                           wire:model="email"
                           placeholder="correo@ejemplo.com"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Teléfono celular <span class="text-gray-400 font-normal">(opcional)</span>
                    </label>
                    <input type="tel"
                           wire:model="telefono"
                           placeholder="Ej. 3001234567"
                           inputmode="numeric"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('telefono') border-red-400 @enderror">
                    @error('telefono')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button wire:click="siguiente"
                        class="bg-[#1B6B2F] hover:bg-[#155724] text-white font-semibold px-6 py-2 rounded-lg transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-[#1B6B2F]">
                    Siguiente
                </button>
            </div>
        @endif

        {{-- ─── STEP 2: Request details ─── --}}
        @if ($paso === 2)
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Detalles de la solicitud</h2>

            <div class="space-y-5">
                {{-- Tipo de solicitud --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de solicitud <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="tipo_solicitud"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('tipo_solicitud') border-red-400 @enderror">
                        <option value="">-- Seleccione --</option>
                        <option value="peticion">Petición</option>
                        <option value="queja">Queja</option>
                        <option value="reclamo">Reclamo</option>
                        <option value="solicitud">Solicitud</option>
                    </select>
                    @error('tipo_solicitud')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal">(mínimo 20 caracteres)</span>
                    </label>
                    <textarea wire:model="descripcion"
                              rows="5"
                              placeholder="Describa detalladamente su solicitud, incluyendo la ubicación del problema y el tiempo que lleva presentándose..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#1B6B2F] focus:border-transparent @error('descripcion') border-red-400 @enderror"></textarea>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ mb_strlen($descripcion) }} / 2000</p>
                    @error('descripcion')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Map pin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Ubicación en el mapa
                        <span class="text-gray-400 font-normal">(opcional — haga clic para colocar el pin)</span>
                    </label>

                    @if ($elemento_id)
                        <p class="text-xs text-[#1B6B2F] bg-green-50 border border-green-200 rounded px-3 py-2 mb-2">
                            Se ha pre-seleccionado el elemento de la infraestructura #{{ $elemento_id }}.
                            El mapa muestra su ubicación exacta.
                        </p>
                    @endif

                    <div id="mapa-pqrs"
                         style="height: 300px; border-radius: 0.5rem; overflow: hidden; border: 1px solid #d1d5db;"
                         wire:ignore
                         x-data="mapaPqrs({
                             lat: {{ $latitud ?? 5.976 }},
                             lng: {{ $longitud ?? -74.594 }},
                             hasPreciseLocation: {{ ($latitud !== null) ? 'true' : 'false' }}
                         })"
                         x-init="init()">
                    </div>

                    @if ($latitud !== null && $longitud !== null)
                        <p class="text-xs text-gray-500 mt-1">
                            Pin en: {{ number_format((float) $latitud, 6) }}, {{ number_format((float) $longitud, 6) }}
                        </p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">No se ha colocado ningún pin.</p>
                    @endif
                </div>
            </div>

            @error('general')
                <p class="text-red-600 text-sm mt-4 text-center">{{ $message }}</p>
            @enderror

            <div class="mt-8 flex justify-between">
                <button wire:click="anterior"
                        class="border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold px-6 py-2 rounded-lg transition-colors">
                    Anterior
                </button>
                <button wire:click="enviar"
                        class="bg-[#1B6B2F] hover:bg-[#155724] text-white font-semibold px-6 py-2 rounded-lg transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-[#1B6B2F]">
                    Enviar solicitud
                </button>
            </div>
        @endif

        {{-- ─── STEP 3: Confirmation ─── --}}
        @if ($paso === 3)
            <div class="text-center py-4">
                <div class="flex justify-center mb-4">
                    <lord-icon src="https://cdn.lordicon.com/pxixoqxa.json"
                        trigger="loop" delay="500" stroke="bold"
                        colors="primary:#1B6B2F"
                        style="width:80px;height:80px"></lord-icon>
                </div>

                <h2 class="text-xl font-bold text-gray-800 mb-2">PQRS radicada exitosamente</h2>
                <p class="text-gray-500 text-sm mb-6">Su solicitud ha sido registrada en el sistema.</p>

                <div class="bg-green-50 border border-[#1B6B2F] rounded-xl px-6 py-5 mb-6 inline-block">
                    <p class="text-sm text-gray-600 mb-1">Número de radicado</p>
                    <p class="text-2xl font-bold text-[#1B6B2F] tracking-wider">{{ $radicadoGenerado }}</p>
                </div>

                <div class="text-left bg-gray-50 rounded-xl p-5 mb-6 space-y-3">
                    <p class="font-semibold text-gray-700 text-sm mb-2">Próximos pasos:</p>
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <span class="w-5 h-5 bg-[#1B6B2F] text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                        <p>Guarde su número de radicado. Lo necesitará para consultar el estado de su solicitud.</p>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <span class="w-5 h-5 bg-[#1B6B2F] text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                        <p>La Secretaría de Obras Públicas revisará su solicitud en un plazo de 15 días hábiles.</p>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <span class="w-5 h-5 bg-[#1B6B2F] text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                        <p>Si proporcionó correo electrónico o teléfono, recibirá notificaciones sobre el avance.</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('pqrs.consultar') }}"
                       class="bg-[#1B6B2F] hover:bg-[#155724] text-white font-semibold px-6 py-2 rounded-lg transition-colors text-sm">
                        Consultar estado de mi PQRS
                    </a>
                    <a href="{{ route('pqrs') }}"
                       class="border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold px-6 py-2 rounded-lg transition-colors text-sm">
                        Radicar otra PQRS
                    </a>
                </div>
            </div>
        @endif

    </div>
</div>

@push('scripts')
@vite(['resources/js/pqrs-pin-map.js'])
@endpush
