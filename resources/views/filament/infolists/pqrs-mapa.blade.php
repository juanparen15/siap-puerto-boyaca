@php($r = $getRecord())

@if ($r->latitud && $r->longitud)
    @php
        $lat = $r->latitud;
        $lng = $r->longitud;
        $gmaps = 'https://www.google.com/maps/search/?api=1&query=' . $lat . ',' . $lng;
        $gdir  = 'https://www.google.com/maps/dir/?api=1&destination=' . $lat . ',' . $lng;
        $waze  = 'https://waze.com/ul?ll=' . $lat . ',' . $lng . '&navigate=yes';
    @endphp

    <div wire:ignore>
        <div id="pqrs-detalle-mapa"
             data-lat="{{ $lat }}"
             data-lng="{{ $lng }}"
             style="height:300px;border-radius:10px;overflow:hidden;border:1px solid rgba(0,0,0,.08);background:#eef1f6;"></div>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:12px;">
        <a href="{{ $gdir }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:8px;background:#3366CC;color:#fff;font-size:13px;font-weight:600;padding:9px 16px;border-radius:8px;text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
            Cómo llegar
        </a>
        <a href="{{ $gmaps }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#0c2a43;border:1px solid rgba(12,42,67,.2);font-size:13px;font-weight:600;padding:9px 16px;border-radius:8px;text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#3366CC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Abrir en Google Maps
        </a>
        <a href="{{ $waze }}" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#0c2a43;border:1px solid rgba(12,42,67,.2);font-size:13px;font-weight:600;padding:9px 16px;border-radius:8px;text-decoration:none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#3366CC" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            Waze
        </a>
        <span style="font-size:12px;color:#6b7280;">{{ number_format((float) $lat, 6) }}, {{ number_format((float) $lng, 6) }}</span>
    </div>
@else
    <p style="color:#6b7280;font-size:14px;">Este reporte no tiene una ubicación georreferenciada.</p>
@endif

@assets
    <link rel="stylesheet" href="{{ asset('maplibre/maplibre-gl.css') }}">
    <script src="{{ asset('maplibre/maplibre-gl.js') }}" defer></script>
@endassets

@script
    <script>
        (function init() {
            var el = document.getElementById('pqrs-detalle-mapa');
            if (! el || el.dataset.init === '1') return;
            if (typeof maplibregl === 'undefined') { setTimeout(init, 150); return; }
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

            new maplibregl.Marker({ color: '#dc2626' })
                .setLngLat([lng, lat])
                .setPopup(new maplibregl.Popup({ offset: 14 }).setHTML(
                    '<div style="font-family:system-ui,sans-serif;padding:6px 8px;font-size:12px;font-weight:600;color:#0c2a43;">Punto reportado</div>'
                ))
                .addTo(map);

            [200, 600, 1200].forEach(function (t) { setTimeout(function () { map.resize(); }, t); });
        })();
    </script>
@endscript
