<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Información de Alumbrado Público — Alcaldía de Puerto Boyacá, Boyacá, Colombia. Consulte el inventario, radique PQRS y acceda a reportes del servicio de alumbrado público municipal.">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/png" href="{{ asset('images/escudo.png') }}">
    <title>SIAP · Alcaldía de Puerto Boyacá</title>
    @vite(['resources/css/app.css', 'resources/css/public.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-white text-gray-800">

    <!-- Sticky Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md border-b-2 border-[#1B6B2F]">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/escudo.png') }}" alt="Escudo Puerto Boyacá" class="h-10 w-auto" onerror="this.style.display='none'">
                <div>
                    <span class="font-bold text-[#1B6B2F] text-lg leading-tight block">SIAP</span>
                    <span class="text-xs text-gray-500 leading-tight block">Alcaldía de Puerto Boyacá</span>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="{{ route('landing') }}" class="text-gray-700 hover:text-[#1B6B2F] transition-colors">Inicio</a>
                <a href="{{ route('mapa') }}" class="text-gray-700 hover:text-[#1B6B2F] transition-colors">Mapa</a>
                <a href="{{ route('pqrs') }}" class="text-gray-700 hover:text-[#1B6B2F] transition-colors">Radicar PQRS</a>
                <a href="{{ route('pqrs.consultar') }}" class="text-gray-700 hover:text-[#1B6B2F] transition-colors">Consultar PQRS</a>
                <a href="{{ route('reportes') }}" class="text-gray-700 hover:text-[#1B6B2F] transition-colors">Reportes</a>
            </div>
        </div>
    </nav>

    <!-- Main Content (padded for sticky nav) -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-[#1B6B2F] text-white py-10 mt-0">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/escudo.png') }}" alt="Escudo" class="h-16 w-auto opacity-90" onerror="this.style.display='none'">
                <div>
                    <p class="font-bold text-lg">Alcaldía de Puerto Boyacá</p>
                    <p class="text-sm text-green-200">Boyacá, Colombia</p>
                </div>
            </div>
            <div>
                <p class="font-semibold mb-2">SIAP — Alumbrado Público</p>
                <p class="text-sm text-green-200">Sistema de Información de Alumbrado Público</p>
                <p class="text-sm text-green-200 mt-1">Conforme a RETILAP Sección 580.1</p>
            </div>
            <div>
                <p class="font-semibold mb-2">Contacto</p>
                <p class="text-sm text-green-200">Secretaría de Obras Públicas</p>
                <p class="text-sm text-green-200">Carrera 4 No. 12-55, Puerto Boyacá</p>
                <p class="text-sm text-green-200">Tel: (608) 555-0000</p>
            </div>
        </div>
        <div class="text-center text-green-300 text-xs mt-8">
            © {{ date('Y') }} Alcaldía de Puerto Boyacá. Todos los derechos reservados.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
