@extends('public.layouts.app')

@section('content')

{{-- Hero Section --}}
<section id="hero" class="relative h-screen overflow-hidden">
    {{-- Leaflet background map --}}
    <div id="hero-map" class="absolute inset-0 z-0"></div>
    {{-- White overlay --}}
    <div class="absolute inset-0 bg-white/75 z-10"></div>
    {{-- Content --}}
    <div class="relative z-20 flex flex-col items-center justify-center h-full text-center px-4">
        <img src="{{ asset('images/escudo.png') }}"
             alt="Escudo de Puerto Boyacá"
             class="h-24 mb-6 drop-shadow-xl animate-fade-in"
             onerror="this.style.display='none'">
        <h1 class="text-4xl md:text-5xl font-bold text-[#1B6B2F] animate-fade-in leading-tight max-w-3xl">
            Sistema de Información de<br>Alumbrado Público
        </h1>
        <p class="text-xl text-gray-600 mt-3 animate-fade-in" style="animation-delay:0.2s">
            Alcaldía de Puerto Boyacá &mdash; Boyacá, Colombia
        </p>

        {{-- Stat counters --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12 animate-fade-in" style="animation-delay:0.4s">
            <div class="stat-card">
                <span class="countup text-4xl font-extrabold text-[#1B6B2F] block" data-target="{{ $stats['total'] }}">0</span>
                <p class="text-sm text-gray-600 mt-1 font-medium">Total Puntos</p>
            </div>
            <div class="stat-card">
                <span class="countup text-4xl font-extrabold text-[#16a34a] block" data-target="{{ $stats['operativos'] }}">0</span>
                <p class="text-sm text-gray-600 mt-1 font-medium">Operativos</p>
            </div>
            <div class="stat-card">
                <span class="countup text-4xl font-extrabold text-red-500 block" data-target="{{ $stats['no_operativos'] }}">0</span>
                <p class="text-sm text-gray-600 mt-1 font-medium">No Operativos</p>
            </div>
            <div class="stat-card">
                <span class="countup text-4xl font-extrabold text-yellow-600 block" data-target="{{ $stats['pqrs_activos'] }}">0</span>
                <p class="text-sm text-gray-600 mt-1 font-medium">PQRS Activos</p>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-8 animate-bounce">
            <svg class="w-8 h-8 text-[#1B6B2F] opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</section>

{{-- Quick Access Cards --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-2">Servicios Disponibles</h2>
        <p class="text-center text-gray-500 mb-12">Accede a la información del alumbrado público de Puerto Boyacá</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('mapa') }}" class="glass-card group">
                <div class="text-4xl mb-4">🗺️</div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Mapa Interactivo</h3>
                <p class="text-sm text-gray-500">Consulta la ubicación de todos los puntos de alumbrado georeferenciados.</p>
            </a>
            <a href="{{ route('pqrs') }}" class="glass-card group">
                <div class="text-4xl mb-4">📋</div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Radicar PQRS</h3>
                <p class="text-sm text-gray-500">Reporta peticiones, quejas, reclamos o solicitudes sobre el alumbrado.</p>
            </a>
            <a href="{{ route('pqrs.consultar') }}" class="glass-card group">
                <div class="text-4xl mb-4">🔍</div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Consultar PQRS</h3>
                <p class="text-sm text-gray-500">Sigue el estado de tu solicitud con el radicado o número de cédula.</p>
            </a>
            <a href="{{ route('reportes') }}" class="glass-card group">
                <div class="text-4xl mb-4">📊</div>
                <h3 class="font-bold text-lg text-[#1B6B2F] mb-2">Reportes</h3>
                <p class="text-sm text-gray-500">Estadísticas y reportes públicos sobre el servicio de alumbrado.</p>
            </a>
        </div>
    </div>
</section>

{{-- About SIAP section --}}
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-[#1B6B2F] mb-6">¿Qué es el SIAP?</h2>
        <p class="text-lg text-gray-600 leading-relaxed">
            El <strong>Sistema de Información de Alumbrado Público (SIAP)</strong> es la plataforma oficial
            de la Alcaldía de Puerto Boyacá para la gestión y consulta del servicio de alumbrado público municipal,
            en cumplimiento del <strong>Reglamento Técnico de Instalaciones Eléctricas RETILAP</strong>,
            Sección 580.1.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-left">
            <div class="flex gap-4">
                <div class="text-[#1B6B2F] text-2xl">⚡</div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-1">Inventario Actualizado</h4>
                    <p class="text-sm text-gray-500">{{ number_format($stats['total']) }} elementos georeferenciados con tecnología GPS.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-[#1B6B2F] text-2xl">🏛️</div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-1">Gestión Transparente</h4>
                    <p class="text-sm text-gray-500">Seguimiento en tiempo real de sus solicitudes y reportes.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="text-[#1B6B2F] text-2xl">📱</div>
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
