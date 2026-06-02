<div>

    {{-- ═══ HERO ═══ --}}
    <section class="relative overflow-hidden bg-[#0f172a] text-white">
        <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
             style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:60px 60px;"></div>
        <div class="pointer-events-none absolute left-1/2 top-1/3 h-[380px] w-[380px] -translate-x-1/2 rounded-full bg-[#1B6B2F] opacity-20 blur-[120px]"></div>

        <div class="relative z-10 mx-auto max-w-3xl px-4 py-20 text-center">
            <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-[#1B6B2F]/40 bg-[#1B6B2F]/15 px-4 py-1.5 text-xs font-semibold uppercase tracking-wider text-green-200">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-300"></span>
                RETILAP 580.1 · Municipio de Puerto Boyacá
            </span>

            <h1 class="text-4xl font-bold leading-tight md:text-5xl">
                Reporta daños en el
                <span class="bg-gradient-to-r from-green-300 to-emerald-400 bg-clip-text text-transparent">alumbrado público</span>
            </h1>
            <p class="mx-auto mt-5 max-w-xl text-lg text-slate-300">
                Ubica el poste o la luminaria en el mapa, cuéntanos qué ocurre y envía tu reporte.
                La Alcaldía lo recibe al instante. Puedes reportar de forma anónima.
            </p>

            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="#mapa-reportar-section"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#1B6B2F] px-6 py-3 font-semibold text-white transition hover:bg-[#155724]">
                    Ver mapa de elementos
                </a>
                <a href="{{ route('pqrs.consultar') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/10 px-6 py-3 font-semibold text-white backdrop-blur transition hover:bg-white/20">
                    Consultar mi reporte
                </a>
            </div>

            {{-- 3 pasos --}}
            <div class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ([
                    ['1', 'Selecciona el elemento', 'Toca un punto en el mapa'],
                    ['2', 'Describe el problema', 'Tipo y detalle del daño'],
                    ['3', 'Envía el reporte', 'Recibe tu número de radicado'],
                ] as [$n, $titulo, $desc])
                    <div class="rounded-xl border border-white/10 bg-white/5 p-4 text-left backdrop-blur">
                        <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#1B6B2F] text-sm font-bold text-white">{{ $n }}</div>
                        <p class="font-semibold text-white">{{ $titulo }}</p>
                        <p class="text-sm text-slate-400">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══ MAPA ═══ --}}
    <section id="mapa-reportar-section" class="bg-white">
        <div class="mx-auto max-w-5xl px-4 pb-6 pt-14 text-center">
            <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">Selecciona un elemento en el mapa</h2>
            <p class="mt-2 text-slate-500">Toca un poste o luminaria para ver su estado y reportar un problema.</p>
        </div>

        <div class="mx-auto max-w-6xl px-4 pb-16">
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 shadow-sm">

                {{-- GPS --}}
                <button type="button" onclick="window.reportarMiUbicacion && window.reportarMiUbicacion()"
                        class="absolute right-4 top-4 z-[500] flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-md transition hover:border-[#1B6B2F] hover:text-[#1B6B2F]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
                        <circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/>
                    </svg>
                    Mi ubicación
                </button>

                {{-- Leyenda --}}
                <div class="absolute bottom-4 left-4 z-[500] rounded-xl border border-slate-100 bg-white/95 px-4 py-2.5 shadow-md backdrop-blur">
                    <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">Estado</p>
                    @foreach ([['#16a34a','Sin reportes'],['#ca8a04','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $c }}"></span>{{ $l }}
                        </div>
                    @endforeach
                </div>

                <div id="mapa-reportar" wire:ignore style="height:78vh;min-height:460px;width:100%;"></div>
            </div>
        </div>
    </section>

    {{-- ═══ FORM SLIDE-OVER ═══ --}}
    @if ($mostrarForm)
        <div class="fixed inset-0 z-[1000] flex justify-end">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="cerrarForm"></div>

            <div class="relative flex h-full w-full max-w-md flex-col overflow-y-auto bg-white p-6 shadow-2xl">
                <button wire:click="cerrarForm" class="absolute right-4 top-4 rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

                <div class="mb-5 pr-8">
                    <span class="inline-flex items-center rounded-full border border-amber-200 bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Reporte ciudadano</span>
                    <h3 class="mt-2 text-lg font-bold text-slate-900">Reportar un problema</h3>
                    @if ($elementoLabel)
                        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-3.5 w-3.5 shrink-0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $elementoLabel }}
                        </p>
                    @endif
                </div>

                <form wire:submit="enviarReporte" class="flex flex-1 flex-col gap-5">
                    {{-- Tipo --}}
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

                    {{-- Descripción --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Descripción <span class="text-red-500">*</span></label>
                        <textarea wire:model="descripcion" rows="4" maxlength="2000"
                                  placeholder="Describe el problema: desde cuándo ocurre, referencia del lugar, etc."
                                  class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none focus:border-[#1B6B2F] focus:ring-2 focus:ring-[#1B6B2F]/20"></textarea>
                        @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Datos opcionales --}}
                    <div x-data="{ abierto: false }" class="rounded-lg border border-slate-200">
                        <button type="button" @click="abierto = !abierto" class="flex w-full items-center justify-between px-3 py-2.5 text-sm font-medium text-slate-600">
                            Tus datos (opcional)
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 transition-transform" :class="abierto && 'rotate-180'"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div x-show="abierto" x-collapse class="space-y-3 px-3 pb-3">
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
            <div class="relative w-full max-w-md rounded-2xl bg-white p-7 text-center shadow-2xl">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-9 w-9"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Reporte radicado</h3>
                <p class="mt-1 text-sm text-slate-500">Gracias por ayudar a mejorar el alumbrado público de tu municipio.</p>

                <div class="my-5 rounded-xl border border-green-200 bg-green-50 px-5 py-4">
                    <p class="text-xs text-slate-500">Número de radicado</p>
                    <p class="text-2xl font-bold tracking-wide text-[#16a34a]">{{ $radicadoGenerado }}</p>
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
