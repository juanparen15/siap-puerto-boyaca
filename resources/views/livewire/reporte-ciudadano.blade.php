<div class="lg-night min-h-screen">

    {{-- ═══════════════════════════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════════════════════════ --}}
    <section class="relative px-4 pt-28 pb-10 text-center lg:pt-36">
        <div class="mx-auto max-w-4xl">
            <span class="lg-glass lg-glass--dark inline-flex items-center gap-2 rounded-full px-4 py-1.5">
                <span class="relative z-[3] flex items-center gap-2 text-sm font-medium text-emerald-200">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-70"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-400"></span>
                    </span>
                    Alumbrado público · Puerto Boyacá
                </span>
            </span>

            <h1 class="font-display mt-7 text-5xl font-bold leading-[1.05] tracking-tight text-white md:text-7xl">
                Tu reporte hace
                <span class="relative mt-1 flex w-full justify-center overflow-hidden pb-3 pt-1 md:pb-5">
                    &nbsp;
                    @foreach (['la diferencia', 'la luz', 'el cambio', 'tu barrio mejor', 'comunidad'] as $palabra)
                        <span data-hero-word class="font-display absolute bg-gradient-to-r from-emerald-300 to-amber-300 bg-clip-text font-extrabold text-transparent">{{ $palabra }}</span>
                    @endforeach
                </span>
            </h1>

            <p class="mx-auto mt-5 max-w-xl text-lg leading-relaxed text-slate-300/90 md:text-xl">
                ¿Una luminaria apagada, un poste caído o un cable expuesto? Ubícalo en el mapa,
                cuéntanos qué pasa y la Alcaldía lo atiende. Toma menos de un minuto.
            </p>

            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="#mapa" class="lg-glass-btn block w-full sm:w-auto">
                    <x-glass tint="green" rounded="rounded-2xl">
                        <span class="flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold text-white">
                            Reportar un daño
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </span>
                    </x-glass>
                </a>
                <a href="{{ route('pqrs.consultar') }}" class="lg-glass-btn block w-full sm:w-auto">
                    <x-glass rounded="rounded-2xl">
                        <span class="flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold text-white">
                            Consultar mi reporte
                        </span>
                    </x-glass>
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="mx-auto mt-14 max-w-4xl">
            <x-glass rounded="rounded-3xl">
                <div class="grid grid-cols-2 md:grid-cols-4">
                    @foreach ([
                        ['total', 'Puntos de luz', 'text-white'],
                        ['operativos', 'Operativos', 'text-emerald-300'],
                        ['no_operativos', 'Fuera de servicio', 'text-rose-300'],
                        ['pqrs_activos', 'Reportes activos', 'text-amber-300'],
                    ] as [$key, $label, $color])
                        <div class="px-2 py-6 text-center">
                            <span class="countup font-display block text-3xl font-extrabold md:text-4xl {{ $color }}" data-target="{{ $stats[$key] }}">0</span>
                            <p class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-slate-300/70">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </x-glass>
        </div>
    </section>

    {{-- ═══ CÓMO FUNCIONA ═══ --}}
    <section class="px-4 py-16">
        <div class="mx-auto grid max-w-5xl grid-cols-1 gap-5 md:grid-cols-3">
            @foreach ([
                ['https://cdn.lordicon.com/dhmavvpz.json', 'Ubica el punto', 'Encuentra el poste o la luminaria en el mapa interactivo.'],
                ['https://cdn.lordicon.com/tbabdzcy.json', 'Describe el daño', 'Elige el tipo de problema y agrega los detalles. Puedes ser anónimo.'],
                ['https://cdn.lordicon.com/pxixoqxa.json', 'Recibe tu radicado', 'Te damos un número para seguir el estado de tu reporte.'],
            ] as $i => [$icono, $titulo, $desc])
                <a href="#mapa" class="lg-glass-btn block">
                    <x-glass rounded="rounded-3xl">
                        <div class="relative p-6">
                            <span class="font-display absolute right-5 top-4 text-4xl font-extrabold text-white/10">{{ $i + 1 }}</span>
                            <lord-icon src="{{ $icono }}" trigger="loop" delay="{{ 800 + $i * 500 }}" stroke="bold"
                                colors="primary:#4ade80,secondary:#fbbf24" style="width:52px;height:52px"></lord-icon>
                            <h3 class="font-display mt-4 text-lg font-bold text-white">{{ $titulo }}</h3>
                            <p class="mt-1 text-sm text-slate-300/80">{{ $desc }}</p>
                        </div>
                    </x-glass>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ═══ MAPA ═══ --}}
    <section id="mapa" class="scroll-mt-24 px-4 pb-20">
        <div class="mx-auto max-w-5xl pb-6 text-center">
            <h2 class="font-display text-3xl font-bold text-white md:text-4xl">Selecciona en el mapa</h2>
            <p class="mt-2 text-slate-300/80">Toca un punto de luz para ver su estado y reportar el problema.</p>
        </div>

        <div class="mx-auto max-w-6xl">
            <x-glass rounded="rounded-[1.75rem]">
                <div class="relative p-2">
                    <button type="button" onclick="window.reportarMiUbicacion && window.reportarMiUbicacion()"
                            class="lg-glass-btn absolute left-5 top-5 z-[5]">
                        <x-glass rounded="rounded-xl">
                            <span class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>
                                Mi ubicación
                            </span>
                        </x-glass>
                    </button>

                    <div class="absolute bottom-5 left-5 z-[5]">
                        <x-glass rounded="rounded-xl">
                            <div class="px-4 py-2.5">
                                <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wide text-slate-200/70">Estado</p>
                                @foreach ([['#16a34a','Sin reportes'],['#f59e0b','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                                    <div class="flex items-center gap-2 text-xs text-slate-100">
                                        <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $c }}"></span>{{ $l }}
                                    </div>
                                @endforeach
                            </div>
                        </x-glass>
                    </div>

                    <div id="mapa-reportar" wire:ignore class="overflow-hidden rounded-[1.4rem]" style="height:74vh;min-height:460px;width:100%;"></div>
                </div>
            </x-glass>
        </div>
    </section>

    {{-- ═══ FORM SLIDE-OVER ═══ --}}
    @if ($mostrarForm)
        <div class="fixed inset-0 z-[1000] flex justify-end">
            <div class="absolute inset-0 bg-black/55 backdrop-blur-sm" wire:click="cerrarForm"></div>

            <div class="relative flex h-full w-full max-w-md flex-col overflow-y-auto border-l border-white/15 bg-[#0b1220]/85 p-6 text-slate-100 shadow-2xl backdrop-blur-2xl">
                <button wire:click="cerrarForm" class="absolute right-4 top-4 rounded-md p-1 text-slate-400 hover:bg-white/10 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <div class="mb-5 pr-8">
                    <span class="inline-flex items-center rounded-full border border-amber-400/30 bg-amber-400/15 px-2.5 py-0.5 text-xs font-medium text-amber-200">Reporte ciudadano</span>
                    <h3 class="font-display mt-2 text-xl font-bold text-white">Reportar un problema</h3>
                    @if ($elementoLabel)
                        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $elementoLabel }}
                        </p>
                    @endif
                </div>

                <form wire:submit="enviarReporte" class="flex flex-1 flex-col gap-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-200">Tipo de problema <span class="text-rose-400">*</span></label>
                        <select wire:model="tipoProblema" class="lg-field w-full rounded-lg px-3 py-2.5 text-sm">
                            <option value="" class="bg-slate-900">— Selecciona —</option>
                            @foreach ($tiposProblema as $val => $label)
                                <option value="{{ $val }}" class="bg-slate-900">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipoProblema') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-200">Descripción <span class="text-rose-400">*</span></label>
                        <textarea wire:model="descripcion" rows="4" maxlength="2000"
                                  placeholder="Describe el problema: desde cuándo ocurre, referencia del lugar, etc."
                                  class="lg-field w-full resize-none rounded-lg px-3 py-2.5 text-sm"></textarea>
                        @error('descripcion') <p class="mt-1 text-sm text-rose-400">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ abierto: false }" class="rounded-lg border border-white/15">
                        <button type="button" @click="abierto = !abierto" class="flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-300">
                            Tus datos (opcional)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 transition-transform" :class="abierto && 'rotate-180'"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div x-show="abierto" x-collapse class="space-y-3 px-3 pb-3" style="display:none;">
                            <p class="text-xs text-slate-400">Si los proporcionas, podremos informarte el avance. Puedes reportar de forma anónima.</p>
                            <input wire:model="nombre" placeholder="Nombre completo" class="lg-field w-full rounded-lg px-3 py-2 text-sm">
                            <input wire:model="cedula" inputmode="numeric" placeholder="Cédula" class="lg-field w-full rounded-lg px-3 py-2 text-sm">
                            <input wire:model="telefono" inputmode="numeric" placeholder="Teléfono" class="lg-field w-full rounded-lg px-3 py-2 text-sm">
                            @error('telefono') <p class="text-sm text-rose-400">{{ $message }}</p> @enderror
                            <input wire:model="email" type="email" placeholder="Correo electrónico" class="lg-field w-full rounded-lg px-3 py-2 text-sm">
                            @error('email') <p class="text-sm text-rose-400">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @error('general') <p class="rounded-lg bg-rose-500/15 px-3 py-2 text-sm text-rose-300">{{ $message }}</p> @enderror

                    <div class="mt-auto flex gap-3 pt-2">
                        <button type="button" wire:click="cerrarForm" class="flex-1 rounded-xl border border-white/20 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">Cancelar</button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="lg-glass-btn flex-1">
                            <x-glass tint="green" rounded="rounded-xl">
                                <span class="block px-5 py-2.5 text-center text-sm font-semibold text-white">
                                    <span wire:loading.remove wire:target="enviarReporte">Enviar reporte</span>
                                    <span wire:loading wire:target="enviarReporte">Enviando…</span>
                                </span>
                            </x-glass>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ═══ CONFIRMACIÓN ═══ --}}
    @if ($radicadoGenerado)
        <div class="fixed inset-0 z-[1100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/55 backdrop-blur-sm" wire:click="reiniciar"></div>
            <div class="relative w-full max-w-md rounded-3xl border border-white/15 bg-[#0b1220]/90 p-7 text-center text-slate-100 shadow-2xl backdrop-blur-2xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-9 w-9"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3 class="font-display text-xl font-bold text-white">Reporte radicado</h3>
                <p class="mt-1 text-sm text-slate-400">Gracias por ayudar a mejorar el alumbrado público de tu municipio.</p>

                <div class="my-5 rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-5 py-4">
                    <p class="text-xs text-slate-400">Número de radicado</p>
                    <p class="font-display text-2xl font-bold tracking-wide text-emerald-300">{{ $radicadoGenerado }}</p>
                </div>

                <p class="mb-5 text-sm text-slate-400">Guarda este número para consultar el estado de tu reporte.</p>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <button wire:click="reiniciar" class="lg-glass-btn flex-1">
                        <x-glass tint="green" rounded="rounded-xl">
                            <span class="block px-5 py-2.5 text-sm font-semibold text-white">Entendido</span>
                        </x-glass>
                    </button>
                    <a href="{{ route('pqrs.consultar') }}" class="flex-1 rounded-xl border border-white/20 px-5 py-2.5 text-sm font-semibold text-slate-200 transition hover:bg-white/10">Consultar estado</a>
                </div>
            </div>
        </div>
    @endif

</div>

@push('scripts')
@vite(['resources/js/reportar.js'])
@endpush
