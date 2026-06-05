<div>

    <!-- hero area start  -->
    <section class="hero-area">
        <div class="container large">
            <div class="hero-area-inner section-spacing-top">
                <div class="hero-content section-spacing-bottom">
                    <div class="award-wrapper fade-anim" data-delay="0.90" data-direction="left" data-ease="back.out(4)">
                        <div class="circle-text-wrapper">
                            <div class="circle-text">
                                <img src="{{ asset('redox/imgs/shape/shape-3.webp') }}" alt="image" class="text">
                                <img src="{{ asset('redox/imgs/shape/shape-2.webp') }}" alt="image" class="icon">
                            </div>
                        </div>
                    </div>
                    <div class="section-header">
                        <div class="section-title-wrapper">
                            <div class="title-wrapper">
                                <h2 class="section-title font-instrumentsans-medium char-anim" data-delay="0.45">
                                    Mejoramos
                                    el alumbrado público
                                    con<span><img class="title-shape-1 fade-anim"
                                            src="{{ asset('redox/imgs/shape/shape-1.webp') }}" alt="image"
                                            {{-- src="{{ asset('images/LOGO ALCALDIA.png') }}" alt="image" --}}
                                            data-direction="right" data-delay="1.80"></span>tu
                                    reporte</h2>
                            </div>
                        </div>
                    </div>
                    <div class="section-content">
                        <div class="features-wrapper-box fade-anim" data-delay="0.75">
                            <div class="features-wrapper">
                                <div class="feature-box">
                                    <div class="content">
                                        <span class="number">92%</span>
                                        <p class="text">Puntos de luz operativos
                                            en el municipio</p>
                                    </div>
                                </div>
                                <div class="feature-box">
                                    <div class="content">
                                        <span class="number">8.500+</span>
                                        <p class="text">Luminarias registradas
                                            en el sistema SIAP</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-wrapper fade-anim" data-delay="0.75">
                            <p class="text">El SIAP es el sistema con el que la Alcaldía de Puerto Boyacá gestiona y
                                atiende el alumbrado público de todo el municipio.</p>
                        </div>
                    </div>
                </div>
                <div class="big-text-wrapper">
                    <h2 class="big-text">SIAP
                    </h2>
                </div>

            </div>
        </div>
    </section>
    <!-- hero area end  -->

    <!-- about area start  -->
    <section class="about-area ">
        <div class="container large">
            <div class="about-area-inner section-spacing">
                <div class="section-content">
                    <div class="shape-1"></div>
                    <div class="shape-2"></div>
                    <div class="shape-3"></div>
                    <div class="shape-4"></div>
                    <div class="section-title-wrapper">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium">SIAP</h2>
                        </div>
                    </div>
                    <div class="text-wrapper">
                        <p class="text">El Sistema de Información de Alumbrado Público centraliza el inventario, las
                            PQRS
                            y la facturación del servicio. Reporta una falla, consulta el estado de tu solicitud y haz
                            seguimiento en línea las 24 horas, incluso de forma anónima.</p>
                    </div>
                    <div class="btn-wrapper ">
                        <a href="{{ route('pqrs') }}" class="rr-btn  btn-text-fli hover-bg-theme">
                            <span class="btn-wrap">
                                <span class="text-one">Reportar un daño</span>
                                <span class="text-two">Reportar un daño</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- about area end  -->

    <!-- video-box  start -->
    <div class="video-box">
        <video id="heroVideo" class="video-area" loop muted autoplay playsinline>
            <source src="{{ asset('video/Alumbrado-Publico-Vereda-Pavitas.mp4') }}" type="video/mp4">
        </video>

        <!-- Flechas para cambiar video -->
        <button type="button" class="video-nav video-prev" aria-label="Anterior">&#10094;</button>
        <button type="button" class="video-nav video-next" aria-label="Siguiente">&#10095;</button>

        <script>
            (function(){
                const sources = [
                    '{{ asset("video/Alumbrado-Publico-Vereda-Pavitas.mp4") }}',
                    '{{ asset("video/Alumbrado Público Puerto Gutiérrez.mp4") }}',
                    '{{ asset("video/Alumbrado Calderón.mp4") }}'
                ];
                let idx = 0;
                const video = document.getElementById('heroVideo');

                function loadIndex(i){
                    idx = (i + sources.length) % sources.length;
                    // replace source and play
                    video.pause();
                    video.querySelector('source').setAttribute('src', sources[idx]);
                    // reload and play
                    video.load();
                    video.play().catch(()=>{});
                }

                document.querySelector('.video-prev').addEventListener('click', function(){ loadIndex(idx - 1); });
                document.querySelector('.video-next').addEventListener('click', function(){ loadIndex(idx + 1); });

                // Optional: keyboard support (left/right)
                document.addEventListener('keydown', function(e){
                    if(e.key === 'ArrowLeft') loadIndex(idx - 1);
                    if(e.key === 'ArrowRight') loadIndex(idx + 1);
                });
            })();
        </script>
        <style>
            .video-box{ position:relative; }
            .video-nav{ position:absolute; top:50%; transform:translateY(-50%); background:rgba(0,0,0,0.4); color:#fff; border:0; padding:10px 14px; cursor:pointer; font-size:22px; }
            .video-prev{ left:18px; }
            .video-next{ right:18px; }
            .video-nav:focus{ outline:none; }
        </style>
    </div>
    <!-- video-box  end -->

    <!-- work area start  -->
    <section class="work-area">

        <!-- text slider area start  -->
        <div class="text-slider-box fade-anim">
            <div class="text-slider">
                <div class="swiper text-slider-active">
                    <div class="swiper-wrapper">
                        @for ($i = 0; $i < 7; $i++)
                            <div class="swiper-slide">
                                <div class="text-slider-item">
                                    <h2 class="title"><span class="dot"></span>Cómo te ayudamos
                                    </h2>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <!-- /text slider area end -->

        <div class="container large">
            <div class="work-area-inner">
                <div class="section-header fade-anim">
                    <div class="section-title-wrapper">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium">Cómo te ayudamos</h2>
                        </div>
                    </div>
                    <div class="text-wrapper">
                        <p class="text">Un servicio cercano y transparente</p>
                    </div>
                    <div class="total-count">
                        <span class="number">(06)</span>
                    </div>
                </div>
                <div class="works-wrapper-box">
                    <div class="works-wrapper-1 fade-anim">
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Reportar">
                                    <a href="{{ route('pqrs') }}"><img
                                            src="{{ asset('redox/imgs/project/image-1.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('pqrs') }}">Reporta una falla</a></h3>
                                <div class="meta">
                                    <span class="tag">Ciudadanía</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Ver mapa">
                                    <a href="{{ route('mapa') }}"><img
                                            src="{{ asset('redox/imgs/project/image-2.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('mapa') }}">Mapa del alumbrado</a></h3>
                                <div class="meta">
                                    <span class="tag">Inventario</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Consultar">
                                    <a href="{{ route('pqrs.consultar') }}"><img
                                            src="{{ asset('redox/imgs/project/image-3.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('pqrs.consultar') }}">Consulta tu PQRS</a></h3>
                                <div class="meta">
                                    <span class="tag">Seguimiento</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Radicar">
                                    <a href="{{ route('pqrs') }}"><img
                                            src="{{ asset('redox/imgs/project/image-4.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('pqrs') }}">Radica una PQRS</a></h3>
                                <div class="meta">
                                    <span class="tag">Trámite</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Ver datos">
                                    <a href="{{ route('reportes') }}"><img
                                            src="{{ asset('redox/imgs/project/image-5.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('reportes') }}">Reportes públicos</a></h3>
                                <div class="meta">
                                    <span class="tag">Transparencia</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-box">
                            <div class="thumb">
                                <div class="image scale" data-cursor-text="Conoce más">
                                    <a href="{{ route('mapa') }}"><img
                                            src="{{ asset('redox/imgs/project/image-6.webp') }}" alt="image"></a>
                                </div>
                            </div>
                            <div class="content">
                                <h3 class="title"><a href="{{ route('mapa') }}">Atención oportuna</a></h3>
                                <div class="meta">
                                    <span class="tag">Cuadrillas</span>
                                    <span class="date">2026</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="all-btn-wrapper fade-anim">
                    <a href="{{ route('mapa') }}" class="rr-btn btn-border hover-bg-theme">
                        <span class="btn-wrap">
                            <span class="text-one">Ver el mapa</span>
                            <span class="text-two">Ver el mapa</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- work area end  -->

    <!-- service area start  -->
    <section class="service-area">
        <div class="container large">
            <div class="service-area-inner section-spacing">
                <div class="section-header">
                    <div class="section-title-wrapper fade-anim">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium word-anim">Nuestros <br>
                                servicios</h2>
                        </div>
                    </div>
                </div>
                <div class="services-wrapper-box">
                    <div class="services-wrapper-1">
                        <div class="service-box fade-anim">
                            <div class="count">
                                <span class="number">(01)</span>
                            </div>

                            <div class="content">
                                <h3 class="title"><a href="{{ route('pqrs') }}">Reporte ciudadano</a></h3>
                                <ul class="service-list">
                                    <li><a href="{{ route('pqrs') }}">Luminarias apagadas</a></li>
                                    <li><a href="{{ route('pqrs') }}">Postes dañados</a></li>
                                    <li><a href="{{ route('pqrs') }}">Cable expuesto</a></li>
                                    <li><a href="{{ route('pqrs') }}">Vandalismo</a></li>
                                    <li><a href="{{ route('pqrs') }}">Reporte anónimo</a></li>
                                </ul>
                            </div>
                            <div class="thumb">
                                <img class="grow" src="{{ asset('redox/imgs/gallery/image-3.webp') }}"
                                    alt="image">
                            </div>
                        </div>
                        <div class="service-box fade-anim">
                            <div class="count">
                                <span class="number">(02)</span>
                            </div>

                            <div class="content">
                                <h3 class="title"><a href="{{ route('pqrs.consultar') }}">Consulta de PQRS</a></h3>
                                <ul class="service-list">
                                    <li><a href="{{ route('pqrs.consultar') }}">Por radicado</a></li>
                                    <li><a href="{{ route('pqrs.consultar') }}">Por cédula</a></li>
                                    <li><a href="{{ route('pqrs.consultar') }}">Estado en línea</a></li>
                                    <li><a href="{{ route('pqrs.consultar') }}">Historial</a></li>
                                    <li><a href="{{ route('pqrs.consultar') }}">Notificaciones</a></li>
                                </ul>
                            </div>
                            <div class="thumb">
                                <img class="grow" src="{{ asset('redox/imgs/gallery/image-4.webp') }}"
                                    alt="image">
                            </div>
                        </div>
                        <div class="service-box fade-anim">
                            <div class="count">
                                <span class="number">(03)</span>
                            </div>

                            <div class="content">
                                <h3 class="title"><a href="{{ route('mapa') }}">Mapa interactivo</a></h3>
                                <ul class="service-list">
                                    <li><a href="{{ route('mapa') }}">Puntos georreferenciados</a></li>
                                    <li><a href="{{ route('mapa') }}">Filtros por zona</a></li>
                                    <li><a href="{{ route('mapa') }}">Estado en tiempo real</a></li>
                                    <li><a href="{{ route('mapa') }}">Ubicación GPS</a></li>
                                    <li><a href="{{ route('mapa') }}">Detalle por punto</a></li>
                                </ul>
                            </div>
                            <div class="thumb">
                                <img class="grow" src="{{ asset('redox/imgs/gallery/image-5.webp') }}"
                                    alt="image">
                            </div>
                        </div>
                        <div class="service-box fade-anim">
                            <div class="count">
                                <span class="number">(04)</span>
                            </div>

                            <div class="content">
                                <h3 class="title"><a href="{{ route('reportes') }}">Transparencia</a></h3>
                                <ul class="service-list">
                                    <li><a href="{{ route('reportes') }}">Inventario público</a></li>
                                    <li><a href="{{ route('reportes') }}">Indicadores</a></li>
                                    <li><a href="{{ route('reportes') }}">Facturación</a></li>
                                    <li><a href="{{ route('reportes') }}">Recaudos</a></li>
                                    <li><a href="{{ route('reportes') }}">Datos abiertos</a></li>
                                </ul>
                            </div>
                            <div class="thumb">
                                <img class="grow" src="{{ asset('redox/imgs/gallery/image-6.webp') }}"
                                    alt="image">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- service area end  -->

    <!-- funfact area start  -->
    <section class="funfact-area fade-anim">
        <div class="container large">
            <div class="funfact-area-inner pin-area">
                <div class="section-header section-spacing-top pin-element">
                    <div class="section-title-wrapper">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium word-anim">SIAP <br>
                                —en cifras</h2>
                        </div>
                    </div>
                </div>
                <div class="funfact-wrapper-box section-spacing">
                    <span class="line-1"></span>
                    <span class="line-2"></span>
                    <span class="line-3"></span>
                    <span class="line-4"></span>
                    <div class="funfact-wrapper">
                        <div class="funfact-item go-visible">
                            <span class="number">8.500+</span>
                            <p class="text">Luminarias registradas en
                                el sistema municipal.</p>
                        </div>
                        <div class="funfact-item go-visible">
                            <span class="number">92%</span>
                            <p class="text">Puntos de luz operativos
                                en todo el municipio.</p>
                        </div>
                        <div class="funfact-item go-visible">
                            <span class="number">24/7</span>
                            <p class="text">Recepción de reportes en
                                línea, todos los días.</p>
                        </div>
                        <div class="funfact-item go-visible">
                            <span class="number">15</span>
                            <p class="text">Días hábiles para la
                                respuesta a tu PQRS.</p>
                        </div>
                        <div class="funfact-item go-visible">
                            <span class="number">100%</span>
                            <p class="text">Trazabilidad y seguimiento
                                de cada solicitud.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- funfact area end  -->

    <!-- client area start  -->
    <section class="client-area">
        <div class="container large">
            <div class="client-area-inner section-spacing-top">
                <div class="section-content">
                    <div class="section-title-wrapper">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium word-anim"><span>Aliados:</span>
                                Trabajamos
                                junto a la comunidad y las entidades del municipio para un mejor
                                alumbrado público.</h2>
                        </div>
                    </div>
                    <div class="text-wrapper fade-anim">
                        <p class="text">La Alcaldía de Puerto Boyacá, la Secretaría de Obras Públicas y la ciudadanía
                            unen esfuerzos para mantener iluminadas todas las calles del municipio.</p>
                    </div>
                </div>
                <div class="client-capsule-wrapper-box" data-t-throwable-scene="true">
                    <div class="client-capsule-wrapper">
                        @foreach (['client-9-light', 'client-10', 'client-11', 'client-12-light', 'client-13', 'client-14', 'client-15-light', 'client-16-light', 'client-17', 'client-18-light', 'client-19', 'client-20-light', 'client-21', 'client-22'] as $logo)
                            <p data-t-throwable-el="">
                                <span class="client-box {{ str_contains($logo, 'light') ? 'bg-theme' : '' }}">
                                    <img src="{{ asset('redox/imgs/client/' . $logo . '.webp') }}" alt="image">
                                </span>
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="lines-wrapper">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- client area end  -->

    <!-- cta area start  -->
    <div class="p-relative overflow-hidden">
        <section class="cta-area">
            <div class="cta-area-inner section-spacing">
                <div class="area-bg"></div>
                <div class="container large">
                    <div class="section-content">
                        <div class="section-title-wrapper">
                            <div class="title-wrapper">
                                <h2 class="section-title font-instrumentsans-medium char-anim"><a
                                        href="{{ route('pqrs') }}">Reporta
                                        ahora</a></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- cta area end  -->

    <!-- productivity area start  -->
    <section class="productivity-area">
        <div class="container large">
            <div class="productivity-area-inner section-spacing">
                <div class="section-content">
                    <div class="section-title-wrapper">
                        <div class="title-wrapper">
                            <h2 class="section-title font-instrumentsans-medium word-anim">Un alumbrado público <br>
                                más <span class="shape-1">seguro</span>, con
                                <span class="shape-2">información</span> y <span class="shape-3">cercanía</span> para
                                cada barrio del <br>
                                municipio
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- productivity area end  -->

    <div class="image-wrapper parallax-view">
        <img class="w-100" src="{{ asset('redox/imgs/gallery/image-7.webp') }}" alt="image" data-speed="0.1">
    </div>

</div>
