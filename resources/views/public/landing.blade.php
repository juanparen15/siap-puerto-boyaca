@extends('public.layouts.app')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════════
     HERO — WebGL2 Shader (animated fire/nebula clouds)
     Adapted from: animated-shader-hero by @atzedent
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="relative w-full overflow-hidden bg-black" style="min-height:100vh;">

    {{-- WebGL canvas (full screen, behind everything) --}}
    <canvas id="shader-hero-canvas"
            class="absolute inset-0 touch-none"
            style="background:#000;display:block;"></canvas>

    {{-- Very subtle dark overlay to improve text contrast --}}
    <div class="absolute inset-0 bg-black/30" style="z-index:1;"></div>

    {{-- Content overlay --}}
    <div class="relative flex flex-col items-center justify-center min-h-screen text-white text-center px-4"
         style="z-index:2;">

        {{-- Trust badge --}}
        <div class="hero-badge-fire hero-anim-down mb-8">
            <span class="badge-dot-fire"></span>
            RETILAP 580.1 &mdash; Municipio de Puerto Boyacá
        </div>

        {{-- Escudo --}}
        <img src="{{ asset('images/escudo.png') }}"
             alt="Escudo de Puerto Boyacá"
             class="h-20 mb-6 drop-shadow-2xl hero-anim-up hero-d200"
             onerror="this.style.display='none'">

        {{-- Headline --}}
        <div class="space-y-1 mb-6">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-none hero-anim-up hero-d400">
                <span class="gradient-text-fire">Alumbrado Público</span>
            </h1>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-none hero-anim-up hero-d600">
                <span class="gradient-text-amber">Puerto Boyacá</span>
            </h1>
        </div>

        {{-- Subtitle --}}
        <p class="max-w-2xl text-lg md:text-xl text-orange-100/80 leading-relaxed font-light hero-anim-up hero-d600 mb-10">
            Plataforma oficial de gestión, consulta y reporte del servicio de alumbrado público municipal. Datos en tiempo real conforme al RETILAP.
        </p>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center hero-anim-up hero-d800">
            <a href="{{ route('mapa') }}" class="btn-hero-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="width:18px;height:18px;">
                    <polygon points="3 11 22 2 13 21 11 13 3 11"/>
                </svg>
                Ver Mapa Interactivo
            </a>
            <a href="{{ route('pqrs') }}" class="btn-hero-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     style="width:18px;height:18px;">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Radicar PQRS
            </a>
        </div>

        {{-- Scroll hint --}}
        <div class="absolute bottom-8 scroll-hint" style="z-index:3;">
            <lord-icon src="https://cdn.lordicon.com/dhmavvpz.json"
                trigger="loop" delay="800" stroke="bold"
                colors="primary:#fb923c"
                style="width:32px;height:32px;opacity:0.6"></lord-icon>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
     STATS BAR — dark background, counters
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="bg-gray-950 border-b border-white/5">
    <div class="max-w-5xl mx-auto px-4 py-10 grid grid-cols-2 md:grid-cols-4 gap-2 divide-x divide-white/5">

        <div class="stat-dark-card stat-dk-green">
            <span class="countup block text-4xl font-extrabold text-white"
                  data-target="{{ $stats['total'] }}">0</span>
            <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-medium">Total Puntos</p>
        </div>

        <div class="stat-dark-card stat-dk-green2">
            <span class="countup block text-4xl font-extrabold text-green-400"
                  data-target="{{ $stats['operativos'] }}">0</span>
            <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-medium">Operativos</p>
        </div>

        <div class="stat-dark-card stat-dk-red">
            <span class="countup block text-4xl font-extrabold text-red-400"
                  data-target="{{ $stats['no_operativos'] }}">0</span>
            <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-medium">No Operativos</p>
        </div>

        <div class="stat-dark-card stat-dk-amber">
            <span class="countup block text-4xl font-extrabold text-amber-400"
                  data-target="{{ $stats['pqrs_activos'] }}">0</span>
            <p class="text-xs text-gray-500 mt-1.5 uppercase tracking-widest font-medium">PQRS Activos</p>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════════════════════
     INTERACTIVE MAP — real-time elements
═══════════════════════════════════════════════════════════════════════════ --}}
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
                       shadow-md text-sm font-medium transition-all duration-200">
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

{{-- ═══════════════════════════════════════════════════════════════════════════
     SERVICES — CardDecorator pattern (Features 3 / 21st.dev)
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="section-heading text-3xl font-bold text-center text-gray-800 mb-2">Servicios Disponibles</h2>
        <p class="section-heading text-center text-gray-500 mb-12">Accede a la información del alumbrado público de Puerto Boyacá</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <a href="{{ route('mapa') }}" class="glass-card group">
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

{{-- ═══════════════════════════════════════════════════════════════════════════
     ABOUT SIAP
═══════════════════════════════════════════════════════════════════════════ --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="section-heading text-3xl font-bold text-[#1B6B2F] mb-6">¿Qué es el SIAP?</h2>
        <p class="animate-on-scroll text-lg text-gray-600 leading-relaxed">
            El <strong>Sistema de Información de Alumbrado Público (SIAP)</strong> es la plataforma oficial
            de la Alcaldía de Puerto Boyacá para la gestión y consulta del servicio de alumbrado público municipal,
            en cumplimiento del <strong>Reglamento Técnico de Instalaciones Eléctricas RETILAP</strong>, Sección 580.1.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-left">

            <div class="feature-item flex gap-4">
                <div class="flex-shrink-0">
                    <lord-icon src="https://cdn.lordicon.com/aulzwxjp.json"
                        trigger="loop" delay="1000" stroke="bold"
                        colors="primary:#1B6B2F" style="width:48px;height:48px"></lord-icon>
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
                        colors="primary:#1B6B2F" style="width:48px;height:48px"></lord-icon>
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
                        colors="primary:#1B6B2F" style="width:48px;height:48px"></lord-icon>
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
@vite(['resources/js/shader-hero.js', 'resources/js/landing.js'])
@endpush
