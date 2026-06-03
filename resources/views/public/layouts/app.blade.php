<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reporta daños en el alumbrado público de Puerto Boyacá. Selecciona el punto en el mapa, describe el problema y la Alcaldía lo recibe al instante.">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/png" href="{{ asset('images/escudo.png') }}">
    <title>SIAP · Alumbrado Público de Puerto Boyacá</title>

    {{-- Fuentes distintivas (Bunny Fonts — privacidad para sitio gov) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700,800|public-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/css/public.css', 'resources/js/app.js'])
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    @stack('styles')
</head>
<body class="lg-bg-mesh text-slate-800 antialiased">

    @php
        $nav = [
            ['landing', 'Inicio', 'https://cdn.lordicon.com/pgirtdfe.json'],
            ['mapa', 'Mapa', 'https://cdn.lordicon.com/dhmavvpz.json'],
            ['reportes', 'Reportes', 'https://cdn.lordicon.com/wdztjihe.json'],
            ['pqrs.consultar', 'Consultar', 'https://cdn.lordicon.com/iuvnsegf.json'],
        ];
    @endphp

    {{-- Header flotante de vidrio líquido (estilo Apple) --}}
    <nav x-data="{ abierto: false }" class="fixed inset-x-0 top-0 z-50 px-3 pt-3 sm:px-4 sm:pt-4">
        <div class="lg-surface lg-sheen mx-auto flex h-14 max-w-6xl items-center justify-between rounded-2xl px-3 sm:px-5">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/escudo.png') }}" alt="Escudo Puerto Boyacá" class="h-9 w-auto" onerror="this.style.display='none'">
                <div class="leading-tight">
                    <span class="block font-display text-base font-bold text-[#1B6B2F]">SIAP</span>
                    <span class="hidden text-[11px] text-slate-500 sm:block">Alcaldía de Puerto Boyacá</span>
                </div>
            </a>

            {{-- Desktop --}}
            <div class="hidden items-center gap-1 md:flex">
                @foreach ($nav as $i => [$ruta, $label, $icono])
                    <a href="{{ route($ruta) }}"
                       class="flex items-center gap-1.5 rounded-full px-3.5 py-2 text-sm font-medium transition {{ request()->routeIs($ruta) ? 'bg-white/80 text-[#1B6B2F] shadow-sm' : 'text-slate-600 hover:bg-white/50 hover:text-[#1B6B2F]' }}">
                        <lord-icon src="{{ $icono }}" trigger="loop" delay="{{ 2200 + $i * 600 }}" stroke="bold"
                            colors="primary:#1B6B2F" style="width:19px;height:19px;pointer-events:none"></lord-icon>
                        {{ $label }}
                    </a>
                @endforeach
                <a href="{{ route('landing') }}#mapa"
                   class="lg-btn-primary ml-2 inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold">
                    Reportar daño
                </a>
            </div>

            <button @click="abierto = !abierto" class="rounded-full p-2 text-slate-600 md:hidden" aria-label="Menú">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="h-6 w-6"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="abierto" x-collapse class="lg-surface lg-sheen mx-auto mt-2 max-w-6xl rounded-2xl p-3 md:hidden" style="display:none;">
            <div class="space-y-1">
                @foreach ($nav as [$ruta, $label, $icono])
                    <a href="{{ route($ruta) }}" class="block rounded-xl px-3 py-2 text-sm font-medium text-slate-700 hover:bg-white/60 hover:text-[#1B6B2F]">{{ $label }}</a>
                @endforeach
                <a href="{{ route('landing') }}#mapa" class="lg-btn-primary mt-1 block rounded-xl px-3 py-2 text-center text-sm font-semibold">Reportar daño</a>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-800 bg-[#0f172a] py-12 text-slate-300">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 px-4 md:grid-cols-3">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/escudo.png') }}" alt="Escudo" class="h-14 w-auto opacity-90" onerror="this.style.display='none'">
                <div>
                    <p class="font-display text-lg font-bold text-white">Alcaldía de Puerto Boyacá</p>
                    <p class="text-sm text-slate-400">Boyacá, Colombia</p>
                </div>
            </div>
            <div>
                <p class="mb-2 font-semibold text-white">SIAP — Alumbrado Público</p>
                <p class="text-sm text-slate-400">Sistema de Información de Alumbrado Público.</p>
                <p class="mt-1 text-sm text-slate-400">Reporta, consulta y haz seguimiento en línea.</p>
            </div>
            <div>
                <p class="mb-2 font-semibold text-white">Contacto</p>
                <p class="text-sm text-slate-400">Secretaría de Obras Públicas</p>
                <p class="text-sm text-slate-400">Carrera 4 No. 12-55, Puerto Boyacá</p>
                <p class="text-sm text-slate-400">Tel: (608) 555-0000</p>
            </div>
        </div>
        <div class="mt-10 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Alcaldía de Puerto Boyacá. Todos los derechos reservados.
        </div>
    </footer>

    @vite(['resources/js/public-animations.js'])
    @stack('scripts')
</body>
</html>
