@extends('public.layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
     HERO — animated rotating word (adaptado de tommyjepsen/animated-hero)
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="relative w-full overflow-hidden bg-white">
    {{-- Halo institucional --}}
    <div class="pointer-events-none absolute left-1/2 top-0 h-[420px] w-[680px] -translate-x-1/2 rounded-full bg-[#1B6B2F]/10 blur-[130px]"></div>

    <div class="container relative z-10 mx-auto px-4">
        <div class="flex flex-col items-center justify-center gap-8 py-24 text-center lg:py-32">

            {{-- Badge --}}
            <a href="{{ route('reportes') }}"
               class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-1.5 text-sm font-medium text-slate-600 transition hover:border-[#1B6B2F] hover:text-[#1B6B2F]">
                RETILAP 580.1 · Municipio de Puerto Boyacá
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>

            {{-- Título con palabra rotante --}}
            <div class="flex flex-col gap-4">
                <h1 class="max-w-3xl text-center text-5xl font-semibold tracking-tight text-slate-900 md:text-7xl">
                    <span class="text-slate-500">El alumbrado público</span>
                    <span class="relative flex w-full justify-center overflow-hidden pb-3 pt-1 md:pb-5 text-center">
                        &nbsp;
                        @foreach (['transparente', 'cercano', 'eficiente', 'confiable', 'tuyo'] as $palabra)
                            <span data-hero-word
                                  class="absolute font-bold text-[#1B6B2F]">{{ $palabra }}</span>
                        @endforeach
                    </span>
                </h1>

                <p class="mx-auto max-w-2xl text-lg leading-relaxed tracking-tight text-slate-500 md:text-xl">
                    Reporta daños en el alumbrado de tu municipio en segundos. Ubica el poste o la
                    luminaria en el mapa, cuéntanos qué ocurre y la Alcaldía lo recibe al instante.
                </p>
            </div>

            {{-- CTAs --}}
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('reportar') }}"
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-lg bg-[#1B6B2F] px-8 text-base font-semibold text-white transition hover:bg-[#155724]">
                    Reportar un daño
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <a href="#mapa"
                   class="inline-flex h-12 items-center justify-center gap-2 rounded-lg border border-slate-300 px-8 text-base font-semibold text-slate-700 transition hover:bg-slate-50">
                    Ver el mapa
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ STATS ═══ --}}
<section class="border-y border-slate-100 bg-slate-50">
    <div class="mx-auto grid max-w-5xl grid-cols-2 divide-x divide-slate-200 px-4 py-10 md:grid-cols-4">
        @foreach ([
            ['total', 'Puntos de luz', 'text-slate-900'],
            ['operativos', 'Operativos', 'text-green-600'],
            ['no_operativos', 'Fuera de servicio', 'text-red-500'],
            ['pqrs_activos', 'Reportes activos', 'text-amber-600'],
        ] as [$key, $label, $color])
            <div class="px-2 text-center">
                <span class="countup block text-4xl font-extrabold {{ $color }}" data-target="{{ $stats[$key] }}">0</span>
                <p class="mt-1.5 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ═══ MAPA (MapLibre GL — estilo mapcn) ═══ --}}
<section id="mapa" class="bg-white">
    <div class="mx-auto max-w-5xl px-4 pb-6 pt-16 text-center">
        <h2 class="text-3xl font-bold text-slate-900">Inventario en tiempo real</h2>
        <p class="mt-2 text-slate-500">
            Toca un punto para ver su estado. ¿Algo no funciona? Repórtalo en un clic.
        </p>
    </div>

    <div class="mx-auto max-w-6xl px-4 pb-16">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 shadow-sm">

            {{-- GPS --}}
            <button type="button" id="landing-gps"
                    class="absolute left-4 top-4 z-[5] flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-md transition hover:border-[#1B6B2F] hover:text-[#1B6B2F]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><circle cx="12" cy="12" r="3"/><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/></svg>
                Mi ubicación
            </button>

            {{-- Leyenda --}}
            <div class="absolute bottom-4 left-4 z-[5] rounded-xl border border-slate-100 bg-white/95 px-4 py-2.5 shadow-md backdrop-blur">
                <p class="mb-1.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">Estado</p>
                @foreach ([['#16a34a','Sin reportes'],['#ca8a04','Con reporte'],['#dc2626','Crítico'],['#94a3b8','Desinstalada']] as [$c,$l])
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="h-2.5 w-2.5 rounded-full" style="background:{{ $c }}"></span>{{ $l }}
                    </div>
                @endforeach
            </div>

            <div id="landing-map" style="height:70vh;min-height:440px;width:100%;"></div>
        </div>
    </div>
</section>

{{-- ═══ SERVICIOS ═══ --}}
<section class="bg-slate-50 py-20">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-center text-3xl font-bold text-slate-900">Servicios disponibles</h2>
        <p class="mb-12 mt-2 text-center text-slate-500">Todo el alumbrado público de Puerto Boyacá, en un solo lugar</p>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['reportar', 'Reportar daño', 'Selecciona un elemento en el mapa y reporta el problema.', 'https://cdn.lordicon.com/tbabdzcy.json'],
                ['mapa', 'Mapa interactivo', 'Consulta todos los puntos de alumbrado georeferenciados.', 'https://cdn.lordicon.com/dhmavvpz.json'],
                ['pqrs.consultar', 'Consultar PQRS', 'Sigue el estado de tu reporte con el radicado.', 'https://cdn.lordicon.com/iuvnsegf.json'],
                ['reportes', 'Reportes', 'Estadísticas públicas del servicio de alumbrado.', 'https://cdn.lordicon.com/wdztjihe.json'],
            ] as $i => [$ruta, $titulo, $desc, $icono])
                <a href="{{ route($ruta) }}"
                   class="group rounded-2xl border border-slate-200 bg-white p-6 text-center transition hover:-translate-y-1 hover:border-[#1B6B2F] hover:shadow-lg">
                    <div class="mb-4 flex justify-center">
                        <lord-icon src="{{ $icono }}" trigger="loop" delay="{{ 600 + $i * 400 }}"
                            stroke="bold" colors="primary:#1B6B2F" style="width:56px;height:56px"></lord-icon>
                    </div>
                    <h3 class="mb-1.5 font-bold text-[#1B6B2F]">{{ $titulo }}</h3>
                    <p class="text-sm text-slate-500">{{ $desc }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
@vite(['resources/js/landing.js'])
@endpush
