<div>

    {{-- ═══════════════════════════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden">
        {{-- Glow ámbar (luz) + halo verde --}}
        <div class="pointer-events-none absolute -right-24 -top-24 h-[460px] w-[460px] rounded-full bg-[#f59e0b]/15 blur-[130px]"></div>
        <div class="pointer-events-none absolute left-1/2 top-1/2 h-[420px] w-[640px] -translate-x-1/2 rounded-full bg-[#1B6B2F]/10 blur-[130px]"></div>
        {{-- Rejilla sutil --}}
        <div class="pointer-events-none absolute inset-0 opacity-[0.04]"
             style="background-image:linear-gradient(#0f172a 1px,transparent 1px),linear-gradient(90deg,#0f172a 1px,transparent 1px);background-size:56px 56px;"></div>

        <div class="relative z-10 mx-auto max-w-4xl px-4 py-24 text-center lg:py-32">
            <span class="lg-surface lg-pill inline-flex items-center gap-2 px-4 py-1.5 text-sm font-medium text-[#1B6B2F]">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#f59e0b] opacity-70"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-[#f59e0b]"></span>
                </span>
                Alumbrado público · Puerto Boyacá
            </span>

            <h1 class="font-display mt-7 text-5xl font-bold leading-[1.05] tracking-tight text-slate-900 md:text-7xl">
                Tu reporte hace
                <span class="relative mt-1 flex w-full justify-center overflow-hidden pb-3 pt-1 md:pb-5">
                    &nbsp;
                    @foreach (['la diferencia', 'la luz', 'el cambio', 'tu barrio mejor', 'comunidad'] as $palabra)
                        <span data-hero-word class="font-display absolute font-extrabold text-[#1B6B2F]">{{ $palabra }}</span>
                    @endforeach
                </span>
            </h1>

            <p class="mx-auto mt-5 max-w-xl text-lg leading-relaxed text-slate-500 md:text-xl">
                ¿Una luminaria apagada, un poste caído o un cable expuesto? Ubícalo en el mapa,
                cuéntanos qué pasa y la Alcaldía lo atiende. Toma menos de un minuto.
            </p>

            <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                <a href="#mapa"
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[#1B6B2F] px-8 text-base font-semibold text-white shadow-lg shadow-[#1B6B2F]/20 transition hover:-translate-y-0.5 hover:bg-[#155724]">
                    Reportar un daño
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="{{ route('pqrs.consultar') }}"
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-8 text-base font-semibold text-slate-700 transition hover:border-[#1B6B2F] hover:text-[#1B6B2F]">
                    Consultar mi reporte
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div class="relative z-10 mx-auto -mb-10 max-w-4xl px-4 pb-12">
            <div class="lg-surface lg-sheen grid grid-cols-2 gap-px overflow-hidden rounded-3xl md:grid-cols-4">
                @foreach ([
                    ['total', 'Puntos de luz', 'text-slate-900'],
                    ['operativos', 'Operativos', 'text-[#16a34a]'],
                    ['no_operativos', 'Fuera de servicio', 'text-[#dc2626]'],
                    ['pqrs_activos', 'Reportes activos', 'text-[#f59e0b]'],
                ] as [$key, $label, $color])
                    <div class="px-2 py-6 text-center">
                        <span class="countup font-display block text-3xl font-extrabold md:text-4xl {{ $color }}" data-target="{{ $stats[$key] }}">0</span>
                        <p class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ CÓMO FUNCIONA ═══ --}}
    <section class="py-16 pt-20">
        <div class="mx-auto max-w-5xl px-4">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach ([
                    ['https://cdn.lordicon.com/dhmavvpz.json', 'Ubica el punto', 'Encuentra el poste o la luminaria en el mapa interactivo.'],
                    ['https://cdn.lordicon.com/tbabdzcy.json', 'Describe el daño', 'Elige el tipo de problema y agrega los detalles. Puedes ser anónimo.'],
                    ['https://cdn.lordicon.com/pxixoqxa.json', 'Recibe tu radicado', 'Te damos un número para seguir el estado de tu reporte.'],
                ] as $i => [$icono, $titulo, $desc])
                    <div class="lg-surface lg-sheen lg-card relative p-6">
                        <span class="font-display absolute right-5 top-4 text-4xl font-extrabold text-slate-300/60">{{ $i + 1 }}</span>
                        <lord-icon src="{{ $icono }}" trigger="loop" delay="{{ 800 + $i * 500 }}" stroke="bold"
                            colors="primary:#1B6B2F" style="width:48px;height:48px"></lord-icon>
                        <h3 class="font-display mt-4 text-lg font-bold text-slate-900">{{ $titulo }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ MAPA ═══ --}}
    <section id="mapa" class="scroll-mt-24 pb-20">
        <div class="mx-auto max-w-5xl px-4 pb-6 text-center">
            <h2 class="font-display text-3xl font-bold text-slate-900 md:text-4xl">Selecciona en el mapa</h2>
            <p class="mt-2 text-slate-500">Toca un punto de luz para ver su estado y reportar el problema.</p>
        </div>

        <div class="mx-auto max-w-6xl px-4">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/50">

                <button type="button" onclick="window.reportarMiUbicacion && window.reportarMiUbicacion()"
                        class="lg-surface lg-sheen absolute left-4 top-4 z-[5] flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:text-[#1B6B2F]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>
                    Mi ubicación
                </button>

                <div class="lg-surface lg-sheen absolute bottom-4 left-4 z-[5] rounded-xl px-4 py-2.5">
                    <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">Estado</p>
                    @foreach ([['#16a34a','Sin reportes'],['#f59e0b','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $c }}"></span>{{ $l }}
                        </div>
                    @endforeach
                </div>

                <div id="mapa-reportar" wire:ignore style="height:74vh;min-height:460px;width:100%;"></div>
            </div>
        </div>
    </section>

    {{-- ═══ FORM SLIDE-OVER ═══ --}}
    @if ($mostrarForm)
        <div class="fixed inset-0 z-[1000] flex justify-end">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="cerrarForm"></div>

            <div class="lg-sheen relative flex h-full w-full max-w-md flex-col overflow-y-auto border-l border-white/60 bg-white/85 p-6 shadow-2xl backdrop-blur-2xl">
                <button wire:click="cerrarForm" class="absolute right-4 top-4 rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <div class="mb-5 pr-8">
                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Reporte ciudadano</span>
                    <h3 class="font-display mt-2 text-xl font-bold text-slate-900">Reportar un problema</h3>
                    @if ($elementoLabel)
                        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $elementoLabel }}
                        </p>
                    @endif
                </div>

                <form wire:submit="enviarReporte" class="flex flex-1 flex-col gap-5">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tipo de problema <span class="text-red-500">*</span></label>
                        <select wire:model="tipoProblema" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1B6B2F] focus:ring-2 focus:ring-[#1B6B2F]/20">
                            <option value="">— Selecciona —</option>
                            @foreach ($tiposProblema as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('tipoProblema') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Descripción <span class="text-red-500">*</span></label>
                        <textarea wire:model="descripcion" rows="4" maxlength="2000"
                                  placeholder="Describe el problema: desde cuándo ocurre, referencia del lugar, etc."
                                  class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1B6B2F] focus:ring-2 focus:ring-[#1B6B2F]/20"></textarea>
                        @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ abierto: false }" class="rounded-lg border border-slate-200">
                        <button type="button" @click="abierto = !abierto" class="flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-600">
                            Tus datos (opcional)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 transition-transform" :class="abierto && 'rotate-180'"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div x-show="abierto" x-collapse class="space-y-3 px-3 pb-3" style="display:none;">
                            <p class="text-xs text-slate-400">Si los proporcionas, podremos informarte el avance. Puedes reportar de forma anónima.</p>
                            <input wire:model="nombre" placeholder="Nombre completo" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1B6B2F]">
                            <input wire:model="cedula" inputmode="numeric" placeholder="Cédula" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1B6B2F]">
                            <input wire:model="telefono" inputmode="numeric" placeholder="Teléfono" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1B6B2F]">
                            @error('telefono') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            <input wire:model="email" type="email" placeholder="Correo electrónico" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-[#1B6B2F]">
                            @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @error('general') <p class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-600">{{ $message }}</p> @enderror

                    <div class="mt-auto flex gap-3 pt-2">
                        <button type="button" wire:click="cerrarForm" class="flex-1 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancelar</button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-[#1B6B2F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#155724] disabled:opacity-60">
                            <span wire:loading.remove wire:target="enviarReporte">Enviar reporte</span>
                            <span wire:loading wire:target="enviarReporte">Enviando…</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- ═══ CONFIRMACIÓN ═══ --}}
    @if ($radicadoGenerado)
        <div class="fixed inset-0 z-[1100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="reiniciar"></div>
            <div class="lg-sheen relative w-full max-w-md rounded-3xl border border-white/60 bg-white/90 p-7 text-center shadow-2xl backdrop-blur-2xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-9 w-9"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3 class="font-display text-xl font-bold text-slate-900">Reporte radicado</h3>
                <p class="mt-1 text-sm text-slate-500">Gracias por ayudar a mejorar el alumbrado público de tu municipio.</p>

                <div class="my-5 rounded-xl border border-green-200 bg-green-50 px-5 py-4">
                    <p class="text-xs text-slate-500">Número de radicado</p>
                    <p class="font-display text-2xl font-bold tracking-wide text-[#16a34a]">{{ $radicadoGenerado }}</p>
                </div>

                <p class="mb-5 text-sm text-slate-500">Guarda este número para consultar el estado de tu reporte.</p>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <button wire:click="reiniciar" class="flex-1 rounded-lg bg-[#1B6B2F] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#155724]">Entendido</button>
                    <a href="{{ route('pqrs.consultar') }}" class="flex-1 rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Consultar estado</a>
                </div>
            </div>
        </div>
    @endif

</div>

@push('scripts')
@vite(['resources/js/reportar.js'])
@endpush
