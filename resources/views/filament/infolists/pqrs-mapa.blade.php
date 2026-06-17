@php($r = $getRecord())

@if ($r->latitud && $r->longitud)
    <div wire:ignore>
        <div id="pqrs-detalle-mapa"
             data-lat="{{ $r->latitud }}"
             data-lng="{{ $r->longitud }}"
             style="height:300px;border-radius:10px;overflow:hidden;border:1px solid rgba(0,0,0,.08);background:#eef1f6;"></div>
    </div>

    @assets
        <link rel="stylesheet" href="{{ asset('maplibre/maplibre-gl.css') }}">
        <script src="{{ asset('maplibre/maplibre-gl.js') }}" defer></script>
    @endassets

    @script
        <script>
            (function init() {
                if (typeof maplibregl === 'undefined') { setTimeout(init, 150); return; }
                var el = document.getElementById('pqrs-detalle-mapa');
                if (!el || el.dataset.init === '1') return;
                el.dataset.init = '1';

                var lat = parseFloat(el.dataset.lat), lng = parseFloat(el.dataset.lng);
                var map = new maplibregl.Map({
                    container: el,
                    style: {
                        version: 8,
                        sources: { osm: { type: 'raster', tiles: ['https://a.tile.openstreetmap.org/{z}/{x}/{y}.png'], tileSize: 256, attribution: '© OpenStreetMap contributors' } },
                        layers: [{ id: 'osm', type: 'raster', source: 'osm' }]
                    },
                    center: [lng, lat],
                    zoom: 16,
                    attributionControl: { compact: true }
                });
                map.addControl(new maplibregl.NavigationControl(), 'top-right');
                new maplibregl.Marker({ color: '#3366CC' }).setLngLat([lng, lat]).addTo(map);
                setTimeout(function () { map.resize(); }, 250);
            })();
        </script>
    @endscript
@else
    <p style="color:#6b7280;font-size:14px;">Este reporte no tiene una ubicación georreferenciada.</p>
@endif
