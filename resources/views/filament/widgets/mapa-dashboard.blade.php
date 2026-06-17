<x-filament-widgets::widget>
    <x-filament::section heading="Mapa de Elementos">
        <div id="mapa-dashboard" style="height: 380px; border-radius: 0.5rem; overflow: hidden; background:#eef1f6;" wire:ignore></div>
    </x-filament::section>

    @script
        <script>
            (function () {
                var JS = '{{ asset('maplibre/maplibre-gl.js') }}';
                var CSS = '{{ asset('maplibre/maplibre-gl.css') }}';

                if (!document.querySelector('link[data-maplibre]')) {
                    var l = document.createElement('link');
                    l.rel = 'stylesheet'; l.href = CSS; l.setAttribute('data-maplibre', '1');
                    document.head.appendChild(l);
                }

                function colorEl(el) {
                    if (el.estado === 'desinstalada') return '#94a3b8';
                    if (el.estado === 'no_operativa' || el.pqrs_activas >= 2) return '#dc2626';
                    if (el.pqrs_activas >= 1) return '#ca8a04';
                    return '#16a34a';
                }
                function toFC(items) {
                    return {
                        type: 'FeatureCollection',
                        features: (items || []).filter(function (e) { return e.latitud && e.longitud; }).map(function (e) {
                            return { type: 'Feature', geometry: { type: 'Point', coordinates: [Number(e.longitud), Number(e.latitud)] }, properties: Object.assign({}, e, { color: colorEl(e) }) };
                        })
                    };
                }

                function build() {
                    var container = document.getElementById('mapa-dashboard');
                    if (!container || container.dataset.init === '1' || typeof maplibregl === 'undefined') return;
                    container.dataset.init = '1';

                    var map = new maplibregl.Map({
                        container: container,
                        style: {
                            version: 8,
                            sources: { osm: { type: 'raster', tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'], tileSize: 256, attribution: '© OpenStreetMap contributors' } },
                            layers: [{ id: 'osm', type: 'raster', source: 'osm' }]
                        },
                        center: [-74.5869, 5.9731],
                        zoom: 13,
                        attributionControl: { compact: true }
                    });
                    map.addControl(new maplibregl.NavigationControl(), 'top-right');

                    var abort = null;
                    function cargar() {
                        var b = map.getBounds();
                        var params = new URLSearchParams({ sw_lat: b.getSouth(), sw_lng: b.getWest(), ne_lat: b.getNorth(), ne_lng: b.getEast() });
                        if (abort) abort.abort();
                        abort = new AbortController();
                        fetch('/api/mapa/elementos?' + params, { signal: abort.signal, headers: { 'Accept': 'application/json' } })
                            .then(function (r) { return r.json(); })
                            .then(function (items) { var src = map.getSource('elementos'); if (src) src.setData(toFC(items)); })
                            .catch(function (e) { if (e.name !== 'AbortError') console.error('Mapa dashboard:', e); });
                    }

                    map.on('load', function () {
                        setTimeout(function () { map.resize(); }, 250);

                        map.addSource('elementos', { type: 'geojson', data: { type: 'FeatureCollection', features: [] }, cluster: true, clusterRadius: 50, clusterMaxZoom: 15 });
                        map.addLayer({ id: 'clusters', type: 'circle', source: 'elementos', filter: ['has', 'point_count'], paint: { 'circle-color': '#3366CC', 'circle-opacity': 0.85, 'circle-radius': ['step', ['get', 'point_count'], 16, 25, 22, 100, 30], 'circle-stroke-width': 3, 'circle-stroke-color': '#ffffff' } });
                        map.addLayer({ id: 'cluster-count', type: 'symbol', source: 'elementos', filter: ['has', 'point_count'], layout: { 'text-field': ['get', 'point_count_abbreviated'], 'text-size': 13 }, paint: { 'text-color': '#ffffff' } });
                        map.addLayer({ id: 'punto', type: 'circle', source: 'elementos', filter: ['!', ['has', 'point_count']], paint: { 'circle-color': ['get', 'color'], 'circle-radius': 7, 'circle-stroke-width': 2, 'circle-stroke-color': '#ffffff' } });

                        map.on('click', 'clusters', function (e) {
                            var f = map.queryRenderedFeatures(e.point, { layers: ['clusters'] });
                            var id = f[0].properties.cluster_id;
                            map.getSource('elementos').getClusterExpansionZoom(id).then(function (zoom) {
                                map.easeTo({ center: f[0].geometry.coordinates, zoom: zoom });
                            });
                        });
                        var cur = function (c) { return function () { map.getCanvas().style.cursor = c; }; };
                        map.on('mouseenter', 'clusters', cur('pointer'));
                        map.on('mouseleave', 'clusters', cur(''));
                        map.on('mouseenter', 'punto', cur('pointer'));
                        map.on('mouseleave', 'punto', cur(''));

                        cargar();
                    });
                    map.on('moveend', cargar);
                }

                if (typeof maplibregl !== 'undefined') {
                    build();
                } else {
                    var existing = document.getElementById('maplibre-cdn-js');
                    if (existing) {
                        existing.addEventListener('load', build);
                    } else {
                        var s = document.createElement('script');
                        s.id = 'maplibre-cdn-js'; s.src = JS; s.onload = build;
                        document.head.appendChild(s);
                    }
                }
            })();
        </script>
    @endscript
</x-filament-widgets::widget>
