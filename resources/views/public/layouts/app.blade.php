<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reporta daños en el alumbrado público de Puerto Boyacá. Ubica el punto en el mapa, describe el problema y la Alcaldía lo recibe al instante.">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO ALCALDIA.png') }}">
    <title>SIAP · Alumbrado Público de Puerto Boyacá</title>

    {{-- Vendor CSS (plantilla Redox) --}}
    <link rel="stylesheet" href="{{ asset('redox/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('redox/vendor/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('redox/vendor/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('redox/vendor/meanmenu.min.css') }}">
    <link rel="stylesheet" href="{{ asset('redox/vendor/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('redox/vendor/animate.min.css') }}">

    {{-- CSS principal de la plantilla --}}
    <link rel="stylesheet" href="{{ asset('redox/css/style.css') }}?v=1.0">

    {{-- Tailwind + estilos de mapa/popup --}}
    @vite(['resources/css/app.css', 'resources/css/public.css', 'resources/js/app.js'])

    {{-- Ajustes SIAP (deben ir DESPUÉS de todo para ganar especificidad) --}}
    <link rel="stylesheet" href="{{ asset('redox/siap-redox.css') }}?v=1.0">

    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    @stack('styles')
</head>

<body class="body-wrapper body-digital-agency">

    {{-- Preloader --}}
    <div id="preloader">
        <div id="container" class="container-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>

    {{-- Scroll to top --}}
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"></path>
        </svg>
    </div>

    {{-- Cursor animado --}}
    <div class="cursor-wrapper relative">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>

    @php
        $nav = [
            ['landing', 'Inicio'],
            ['mapa', 'Mapa'],
            ['reportes', 'Reportes'],
            ['pqrs.consultar', 'Consultar'],
            ['pqrs', 'Radicar PQRS'],
        ];
    @endphp

    {{-- Panel lateral (menú móvil) --}}
    <aside class="fix">
        <div class="side-info">
            <div class="side-info-content">
                <div class="offset-widget offset-header">
                    <div class="offset-logo">
                        <a href="{{ route('landing') }}">
                            <img src="{{ asset('images/LOGO ALCALDIA.png') }}" alt="Escudo Puerto Boyacá" style="height:48px;width:auto;">
                        </a>
                    </div>
                    <button id="side-info-close" class="side-info-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mobile-menu d-xl-none fix"></div>
                <div class="offset-button">
                    <a href="{{ route('pqrs') }}" class="rr-btn hover-bg-theme">
                        <span class="btn-wrap">
                            <span class="text-one">Reportar daño</span>
                            <span class="text-two">Reportar daño</span>
                        </span>
                    </a>
                </div>
                <div class="offset-widget-box">
                    <h2 class="title">Contacto</h2>
                    <div class="contact-meta">
                        <div class="contact-item">
                            <span class="icon"><i class="fa-solid fa-location-dot"></i></span>
                            <span class="text">Secretaría de Obras Públicas, Puerto Boyacá</span>
                        </div>
                        <div class="contact-item">
                            <span class="icon"><i class="fa-solid fa-envelope"></i></span>
                            <span class="text"><a href="mailto:obraspublicas@puertoboyaca-boyaca.gov.co">obraspublicas@puertoboyaca-boyaca.gov.co</a></span>
                        </div>
                        <div class="contact-item">
                            <span class="icon"><i class="fa-solid fa-phone"></i></span>
                            <span class="text"><a href="tel:6085550000">(608) 555-0000</a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <div class="offcanvas-overlay"></div>

    {{-- Header --}}
    <header class="header-area header-sticky">
        <div class="header-main">
            <div class="container large">
                <div class="header-area__inner">
                    <div class="header__logo">
                        <a href="{{ route('landing') }}" class="d-flex align-items-center" style="gap:12px;">
                            <img src="{{ asset('images/LOGO ALCALDIA.png') }}" class="siap-logo" alt="Escudo Puerto Boyacá" onerror="this.style.display='none'">
                            {{-- <span class="header-brand-text">
                                <span class="brand-name">SIAP</span>
                                <span class="brand-sub">Alumbrado Público</span>
                            </span> --}}
                        </a>
                    </div>
                    <div class="header__nav">
                        <nav class="main-menu">
                            <ul>
                                @foreach ($nav as [$ruta, $label])
                                    <li class="{{ request()->routeIs($ruta) ? 'current' : '' }}">
                                        <a href="{{ route($ruta) }}">{{ $label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </div>
                    <div class="header__button">
                        <a href="{{ route('pqrs') }}" class="rr-btn hover-bg-theme">
                            <span class="btn-wrap">
                                <span class="text-one">Reportar daño</span>
                                <span class="text-two">Reportar daño</span>
                            </span>
                        </a>
                    </div>
                    <div class="header__navicon d-xl-none">
                        <button class="side-toggle">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="has-smooth" id="has_smooth"></div>
    <div id="smooth-wrapper">
        <div id="smooth-content">

            <main>
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="footer-area">
                <div class="container large">
                    <div class="footer-top-inner">
                        <div class="footer-logo">
                            <a href="{{ route('landing') }}">
                                <img src="{{ asset('images/LOGO ALCALDIA.png') }}" alt="Escudo Puerto Boyacá" style="height:70px;width:auto;">
                            </a>
                        </div>
                        <div class="info-text">
                            <div class="text-wrapper">
                                <p class="text">El SIAP es el Sistema de Información de Alumbrado Público del municipio de
                                    Puerto Boyacá: reporta, consulta y haz seguimiento en línea al servicio.</p>
                            </div>
                            <div class="info-link">
                                <a href="mailto:obraspublicas@puertoboyaca-boyaca.gov.co">obraspublicas@puertoboyaca-boyaca.gov.co</a>
                            </div>
                        </div>
                    </div>
                    <div class="footer-widget-wrapper-box">
                        <div class="footer-widget-wrapper">
                            <div class="footer-widget-box">
                                <h2 class="title">Servicios</h2>
                                <ul class="footer-nav-list">
                                    <li><a href="{{ route('pqrs') }}">Reportar daño</a></li>
                                    <li><a href="{{ route('mapa') }}">Mapa de alumbrado</a></li>
                                    <li><a href="{{ route('pqrs.consultar') }}">Consultar reporte</a></li>
                                    <li><a href="{{ route('pqrs') }}">Radicar PQRS</a></li>
                                    <li><a href="{{ route('reportes') }}">Reportes públicos</a></li>
                                </ul>
                            </div>
                            <div class="footer-widget-box">
                                <h2 class="title">Municipio</h2>
                                <ul class="footer-nav-list">
                                    <li><a href="https://www.puertoboyaca-boyaca.gov.co" target="_blank" rel="noopener">Alcaldía de Puerto Boyacá</a></li>
                                    <li><a href="https://www.puertoboyaca-boyaca.gov.co" target="_blank" rel="noopener">Trámites y servicios</a></li>
                                    <li><a href="{{ route('reportes') }}">Transparencia</a></li>
                                    <li><a href="{{ url('/admin') }}"><i class="fa-solid fa-lock" style="font-size:11px;margin-right:6px;"></i>Acceso funcionarios</a></li>
                                </ul>
                            </div>
                            <div class="footer-widget-box">
                                <h2 class="title">Contacto</h2>
                                <ul class="footer-nav-list">
                                    <li>Secretaría de Obras Públicas</li>
                                    <li>Puerto Boyacá, Boyacá</li>
                                    <li><a href="tel:6085550000">(608) 555-0000</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright-area">
                    <div class="copyright-area-inner">
                        <div class="copyright-text">
                            <p class="text">© {{ date('Y') }} Alcaldía de Puerto Boyacá ·
                                <a href="{{ url('/admin') }}">Acceso funcionarios</a> ·
                                Desarrollado por <a href="https://renbel.com.co" target="_blank" rel="noopener">RENBEL S.A.S.</a></p>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    {{-- Vendor JS (plantilla Redox) --}}
    <script src="{{ asset('redox/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/gsap.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/ScrollSmoother.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/ScrollToPlugin.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/SplitText.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/TextPlugin.js') }}"></script>
    <script src="{{ asset('redox/vendor/customEase.js') }}"></script>
    <script src="{{ asset('redox/vendor/Flip.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('redox/vendor/backToTop.js') }}"></script>
    <script src="{{ asset('redox/vendor/matter.js') }}"></script>
    <script src="{{ asset('redox/vendor/throwable.js') }}"></script>
    <script src="{{ asset('redox/js/magiccursor.js') }}"></script>
    <script src="{{ asset('redox/js/main.js') }}?v=1.0"></script>

    {{-- Refrescar GSAP cuando Livewire cambia la altura del contenido --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            const refresh = () => { if (window.ScrollTrigger) window.ScrollTrigger.refresh(); };
            Livewire.hook('morph.updated', () => setTimeout(refresh, 60));
            Livewire.hook('commit', ({ respond }) => respond(() => setTimeout(refresh, 120)));
        });

        // El smooth-scroll no debe robar la rueda al interactuar con un mapa:
        // pausamos el smoother mientras el cursor está sobre cualquier mapa.
        document.addEventListener('mouseover', (e) => {
            if (e.target.closest && e.target.closest('.siap-map-canvas')) {
                const sm = window.ScrollSmoother && window.ScrollSmoother.get && window.ScrollSmoother.get();
                if (sm) sm.paused(true);
            }
        });
        document.addEventListener('mouseout', (e) => {
            const from = e.target.closest && e.target.closest('.siap-map-canvas');
            const to = e.relatedTarget && e.relatedTarget.closest && e.relatedTarget.closest('.siap-map-canvas');
            if (from && !to) {
                const sm = window.ScrollSmoother && window.ScrollSmoother.get && window.ScrollSmoother.get();
                if (sm) sm.paused(false);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
