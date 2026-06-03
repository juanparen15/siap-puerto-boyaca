<x-filament-widgets::widget>
    <x-filament::section heading="Mapa de Elementos">
        <div id="mapa-dashboard" style="height: 350px; border-radius: 0.5rem; overflow: hidden;" wire:ignore></div>

        <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.css">
        <script>
        (function () {
            function initDashboardMap() {
                var container = document.getElementById('mapa-dashboard');
                if (!container || container.dataset.init === '1' || typeof maplibregl === 'undefined') return;
                container.dataset.init = '1';

                var map = new maplibregl.Map({
                    container: 'mapa-dashboard',
                    style: {
                        version: 8,
                        sources: {
                            osm: {
                                type: 'raster',
                                tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'],
                                tileSize: 256,
                                attribution: '© OpenStreetMap contributors'
                            }
                        },
                        layers: [{ id: 'osm', type: 'raster', source: 'osm' }]
                    },
                    center: [-74.579, 5.977],
                    zoom: 13,
                    attributionControl: { compact: true }
                });
                map.addControl(new maplibregl.NavigationControl(), 'top-right');

                map.on('load', function () {
                    fetch('/api/mapa/elementos')
                        .then(function (r) { return r.json(); })
                        .then(function (items) {
                            var features = items
                                .filter(function (el) { return el.latitud && el.longitud; })
                                .map(function (el) {
                                    var color = el.estado === 'no_operativa' ? '#dc2626'
                                        : el.estado === 'desinstalada' ? '#94a3b8' : '#16a34a';
                                    return {
                                        type: 'Feature',
                                        geometry: { type: 'Point', coordinates: [Number(el.longitud), Number(el.latitud)] },
                                        properties: { color: color }
                                    };
                                });
                            map.addSource('elementos', { type: 'geojson', data: { type: 'FeatureCollection', features: features } });
                            map.addLayer({
                                id: 'punto', type: 'circle', source: 'elementos',
                                paint: { 'circle-color': ['get', 'color'], 'circle-radius': 4, 'circle-stroke-width': 1, 'circle-stroke-color': '#fff' }
                            });
                        });
                });
            }

            if (typeof maplibregl === 'undefined') {
                var s = document.createElement('script');
                s.src = 'https://unpkg.com/maplibre-gl@5/dist/maplibre-gl.js';
                s.onload = initDashboardMap;
                document.head.appendChild(s);
            } else {
                initDashboardMap();
            }
        })();
        </script>
    </x-filament::section>
</x-filament-widgets::widget>
