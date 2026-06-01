@extends('public.layouts.app')

@section('content')

{{-- ─── HERO SECTION ──────────────────────────────────────────────────────── --}}
<section id="hero" class="relative min-h-screen overflow-hidden">

    {{-- 1. Leaflet background map --}}
    <div id="hero-map" class="absolute inset-0 z-0"></div>

    {{-- 2. White overlay --}}
    <div class="absolute inset-0 bg-white/78 z-10"></div>

    {{-- 3. Subtle dot-grid over the overlay --}}
    <div class="absolute inset-0 z-[12] hero-grid pointer-events-none"></div>

    {{-- 4. Top accent line (animated via Motion.js) --}}
    <div id="hero-accent-line"
         class="hero-accent-line absolute z-[14] pointer-events-none"
         style="top:64px;left:0;width:0;"></div>

    {{-- 5. Floating decorative circle (right side) --}}
    <div class="hero-circle absolute z-[13] pointer-events-none"
         style="top:15%;right:-8%;width:420px;height:420px;"></div>

    {{-- 6. Small floating dot (bottom right) --}}
    <div class="hero-dot absolute z-[13] pointer-events-none"
         style="bottom:22%;right:12%;width:70px;height:70px;opacity:0.6;"></div>

    {{-- 7. Rotating diamond (bottom left) --}}
    <div class="hero-diamond absolute z-[13] pointer-events-none"
         style="bottom:12%;left:6%;width:100px;height:100px;opacity:0.5;"></div>

    {{-- 8. Main content --}}
    <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4 min-h-screen">

        {{-- Badge institucional --}}
        <div class="hero-badge animate-fade-in mb-6">
            <span class="badge-dot"></span>
            RETILAP 580.1 &mdash; Puerto Boyacá
        </div>

        {{-- Escudo --}}
        <img src="{{ asset('images/escudo.png') }}"
             alt="Escudo de Puerto Boyacá"
             class="h-20 mb-5 drop-shadow-xl animate-fade-in"
             onerror="this.style.display='none'">

        {{-- Title --}}
        <h1 class="text-4xl md:text-5xl font-bold text-[#1B6B2F] animate-fade-in leading-tight max-w-3xl">
            Sistema de Información de<br>Alumbrado Público
        </h1>
        <p class="text-lg text-gray-500 mt-3 animate-fade-in" style="animation-delay:0.2s">
            Alcaldía de Puerto Boyacá &mdash; Boyacá, Colombia
        </p>

        {{-- Stat counters (v2 with accent bars) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-12 animate-fade-in w-full max-w-2xl" style="animation-delay:0.4s">

            <div class="stat-card-v2 stat-v2-green">
                <span class="countup text-4xl font-extrabold text-[#1B6B2F] block"
                      data-target="{{ $stats['total'] }}">0</span>
                <p class="text-xs text-gray-500 mt-1.5 font-semibold uppercase tracking-wide">Total Puntos</p>
            </div>

            <div class="stat-card-v2 stat-v2-lightgreen">
                <span class="countup text-4xl font-extrabold text-green-600 block"
                      data-target="{{ $stats['operativos'] }}">0</span>
                <p class="text-xs text-gray-500 mt-1.5 font-semibold uppercase tracking-wide">Operativos</p>
            </div>

            <div class="stat-card-v2 stat-v2-red">
                <span class="countup text-4xl font-extrabold text-red-500 block"
                      data-target="{{ $stats['no_operativos'] }}">0</span>
                <p class="text-xs text-gray-500 mt-1.5 font-semibold uppercase tracking-wide">No Operativos</p>
            </div>

            <div class="stat-card-v2 stat-v2-yellow">
                <span class="countup text-4xl font-extrabold text-yellow-600 block"
                      data-target="{{ $stats['pqrs_activos'] }}">0</span>
                <p class="text-xs text-gray-500 mt-1.5 font-semibold uppercase tracking-wide">PQRS Activos</p>
            </div>

        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 scroll-hint">
            <lord-icon src="https://cdn.lordicon.com/dhmavvpz.json"
                trigger="loop" delay="800" stroke="bold"
                colors="primary:#1B6B2F"
                style="width:32px;height:32px;opacity:0.5"></lord-icon>
        </div>
    </div>

</section>

{{-- ─── INTERACTIVE MAP SECTION ───────────────────────────────────────────── --}}
<section class="bg-white">

    <div class="max-w-5xl mx-auto px-4 pt-16 pb-5 text-center">
        <h2 class="section-heading text-3xl font-bold text-gray-800 mb-2">
            Alumbrado Público en Tiempo Real
        </h2>
        <p class="animate-on-scroll text-gray-500 text-base">
            Toca cualquier punto del mapa para ver su estado o reportar un problema directamente
        </p>
    </div>

    <div class="relative" x-data="mapaLanding()" x-init="init()">

        {{-- GPS Button --}}
        <button @click="miUbicacion()"
                class="absolute top-4 right-4 z-[1000] flex items-center gap-2
                       bg-white hover:bg-green-50 border border-gray-200 hover:border-[#1B6B2F]
                       text-gray-600 hover:text-[#1B6B2F] rounded-xl px-4 py-2.5
                       shadow-md text-sm font-medium transition-all duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="w-4 h-4 flex-shrink-0">
                <circle cx="12" cy="12" r="3"/>
                <line x1="12" y1="2"  x2="12" y2="6"/>
                <line x1="12" y1="18" x2="12" y2="22"/>
                <line x1="2"  y1="12" x2="6"  y2="12"/>
                <line x1="18" y1="12" x2="22" y2="12"/>
            </svg>
            Mi ubicación
        </button>

        {{-- Legend --}}
        <div class="absolute bottom-4 left-4 z-[1000] bg-white/95 backdrop-blur-sm
                    rounded-xl shadow-md px-4 py-3 border border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Estado</p>
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-xs text-gray-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-green-600 flex-shrink-0"></span>Operativa
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 flex-shrink-0"></span>No Operativa
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-700">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400 flex-shrink-0"></span>Desinstalada
                </div>
            </div>
        </div>

        <div id="mapa-landing" style="height:520px;width:100%;"></div>
    </div>

    <div class="text-center py-4 border-b border-gray-100">
        <p class="text-xs text-gray-400">
            Filtros avanzados y vista completa en
            <a href="{{ route('mapa') }}" class="text-[#1B6B2F] font-medium hover:underline">Mapa de Alumbrado</a>
        </p>
    </div>

</section>

{{-- ─── QUICK ACCESS CARDS (CardDecorator pattern) ──────────────────────────── --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="section-heading text-3xl font-bold text-center text-gray-800 mb-2">Servicios Disponibles</h2>
        <p class="section-heading text-center text-gray-500 mb-12">Accede a la información del alumbrado público de Puerto Boyacá</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="{{ route('mapa') }}" class="glass-card group">
                {{-- CardDecorator: grid radial + icono flotante --}}
                <div class="card-decorator mb-5 mx-auto">
                    <div class="card-decorator-grid"></div>
                    <div class="card-decorator-icon">
                        <lord-icon src="https://cdn.lordicon.com/dhmavvpz.json"
                            trigger="loop" delay="500" stroke="bold"
                            colors="primary:#1B6B2F"
                            style="width:44px;height:44px"></lord-icon>
                    </div>
                </div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Mapa Interactivo</h3>
                <p class="text-sm text-gray-500">Consulta la ubicación de todos los puntos de alumbrado georeferenciados.</p>
            </a>

            <a href="{{ route('pqrs') }}" class="glass-card group">
                <div class="card-decorator mb-5 mx-auto">
                    <div class="card-decorator-grid"></div>
                    <div class="card-decorator-icon">
                        <lord-icon src="https://cdn.lordicon.com/vwzukuhn.json"
                            trigger="loop" delay="1200" stroke="bold"
                            colors="primary:#1B6B2F"
                            style="width:44px;height:44px"></lord-icon>
                    </div>
                </div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Radicar PQRS</h3>
                <p class="text-sm text-gray-500">Reporta peticiones, quejas, reclamos o solicitudes sobre el alumbrado.</p>
            </a>

            <a href="{{ route('pqrs.consultar') }}" class="glass-card group">
                <div class="card-decorator mb-5 mx-auto">
                    <div class="card-decorator-grid"></div>
                    <div class="card-decorator-icon">
                        <lord-icon src="https://cdn.lordicon.com/iuvnsegf.json"
                            trigger="loop" delay="900" stroke="bold"
                            colors="primary:#1B6B2F"
                            style="width:44px;height:44px"></lord-icon>
                    </div>
                </div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Consultar PQRS</h3>
                <p class="text-sm text-gray-500">Sigue el estado de tu solicitud con el radicado o número de cédula.</p>
            </a>

            <a href="{{ route('reportes') }}" class="glass-card group">
                <div class="card-decorator mb-5 mx-auto">
                    <div class="card-decorator-grid"></div>
                    <div class="card-decorator-icon">
                        <lord-icon src="https://cdn.lordicon.com/wdztjihe.json"
                            trigger="loop" delay="1500" stroke="bold"
                            colors="primary:#1B6B2F"
                            style="width:44px;height:44px"></lord-icon>
                    </div>
                </div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Reportes</h3>
                <p class="text-sm text-gray-500">Estadísticas y reportes públicos sobre el servicio de alumbrado.</p>
            </a>

        </div>
    </div>
</section>

{{-- ─── ABOUT SIAP ─────────────────────────────────────────────────────────── --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="section-heading text-3xl font-bold text-[#1B6B2F] mb-6">¿Qué es el SIAP?</h2>
        <p class="animate-on-scroll text-lg text-gray-600 leading-relaxed">
            El <strong>Sistema de Información de Alumbrado Público (SIAP)</strong> es la plataforma oficial
            de la Alcaldía de Puerto Boyacá para la gestión y consulta del servicio de alumbrado público municipal,
            en cumplimiento del <strong>Reglamento Técnico de Instalaciones Eléctricas RETILAP</strong>,
            Sección 580.1.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-left">

            <div class="feature-item flex gap-4">
                <div class="flex-shrink-0">
                    <lord-icon src="https://cdn.lordicon.com/aulzwxjp.json"
                        trigger="loop" delay="1000" stroke="bold"
                        colors="primary:#1B6B2F"
                        style="width:48px;height:48px"></lord-icon>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-1">Inventario Actualizado</h4>
                    <p class="text-sm text-gray-500">{{ number_format($stats['total']) }} elementos georeferenciados con tecnología GPS.</p>
                </div>
            </div>

            <div class="feature-item flex gap-4">
                <div class="flex-shrink-0">
                    <lord-icon src="https://cdn.lordicon.com/kugoanlw.json"
                        trigger="loop" delay="1800" stroke="bold"
                        colors="primary:#1B6B2F"
                        style="width:48px;height:48px"></lord-icon>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-1">Gestión Transparente</h4>
                    <p class="text-sm text-gray-500">Seguimiento en tiempo real de sus solicitudes y reportes.</p>
                </div>
            </div>

            <div class="feature-item flex gap-4">
                <div class="flex-shrink-0">
                    <lord-icon src="https://cdn.lordicon.com/qctplryk.json"
                        trigger="loop" delay="2500" stroke="bold"
                        colors="primary:#1B6B2F"
                        style="width:48px;height:48px"></lord-icon>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-1">Acceso Ciudadano</h4>
                    <p class="text-sm text-gray-500">Información pública disponible 24/7 desde cualquier dispositivo.</p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
@vite(['resources/js/landing.js'])
@endpush
