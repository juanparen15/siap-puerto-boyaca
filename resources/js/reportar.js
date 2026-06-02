import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

function escHtml(str) {
    return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

const TIPO_LABEL = {
    luminaria: 'Luminaria', poste: 'Poste', reflector: 'Reflector',
    sendero_peatonal: 'Sendero peatonal', campo_deportivo: 'Campo deportivo',
    luminaria_parque: 'Luminaria de parque',
};

// Estado visual según estado del elemento + PQRS activas
function colorElemento(el) {
    if (el.estado === 'desinstalada') return '#94a3b8';
    if (el.estado === 'no_operativa' || el.pqrs_activas >= 2) return '#dc2626';
    if (el.pqrs_activas >= 1) return '#ca8a04';
    return '#16a34a';
}

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('mapa-reportar');
    if (!el) return;

    const CENTER = [5.9731, -74.5869];
    const map = L.map(el).setView(CENTER, 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const markersLayer = L.layerGroup().addTo(map);
    let userMarker = null;
    let abortController = null;

    function buildPopup(elm) {
        const color = colorElemento(elm);
        const estadoLabel = elm.estado === 'operativa' ? 'Operativa'
            : elm.estado === 'no_operativa' ? 'Fuera de servicio' : 'Desinstalada';
        const tipo = escHtml(TIPO_LABEL[elm.tipo] || elm.tipo || '');
        const rotulo = escHtml(elm.rotulo || ('Elemento #' + elm.id));

        const node = document.createElement('div');
        node.style.fontFamily = 'system-ui, sans-serif';
        node.style.minWidth = '205px';
        node.innerHTML = `
            <div style="padding:12px 13px;">
                <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
                    <span style="width:9px;height:9px;border-radius:9999px;background:${color};display:inline-block;"></span>
                    <strong style="font-size:13px;color:#0f172a;">${rotulo}</strong>
                </div>
                <p style="margin:0 0 3px;font-size:12px;color:#475569;">${tipo}${elm.potencia_w ? ' · ' + escHtml(String(elm.potencia_w)) + ' W' : ''}</p>
                <p style="margin:0 0 10px;font-size:11px;color:#64748b;">Estado: ${estadoLabel}${elm.pqrs_activas > 0 ? ' · ' + elm.pqrs_activas + ' reporte(s) activo(s)' : ''}</p>
                <button type="button" style="width:100%;background:#1B6B2F;color:#fff;border:none;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;">Reportar un problema</button>
            </div>`;

        node.querySelector('button').addEventListener('click', () => {
            map.closePopup();
            if (window.Livewire) {
                window.Livewire.dispatch('seleccionar-elemento', {
                    id: elm.id,
                    tipo: elm.tipo || '',
                    rotulo: elm.rotulo || '',
                    lat: elm.latitud ? parseFloat(elm.latitud) : null,
                    lng: elm.longitud ? parseFloat(elm.longitud) : null,
                });
            }
        });

        return node;
    }

    function cargar() {
        const b = map.getBounds();
        const params = new URLSearchParams({
            sw_lat: b.getSouth(), sw_lng: b.getWest(),
            ne_lat: b.getNorth(), ne_lng: b.getEast(),
        });
        abortController?.abort();
        abortController = new AbortController();
        fetch('/api/mapa/elementos?' + params, { signal: abortController.signal })
            .then(r => r.json())
            .then(elementos => {
                markersLayer.clearLayers();
                elementos.forEach(elm => {
                    if (!elm.latitud || !elm.longitud) return;
                    const marker = L.circleMarker([parseFloat(elm.latitud), parseFloat(elm.longitud)], {
                        radius: 7, fillColor: colorElemento(elm), color: '#fff', weight: 2, fillOpacity: 0.9,
                    });
                    marker.bindPopup(buildPopup(elm), { maxWidth: 260, minWidth: 205 });
                    markersLayer.addLayer(marker);
                });
            })
            .catch(err => { if (err.name !== 'AbortError') console.error('Error cargando mapa:', err); });
    }

    map.on('moveend', cargar);
    cargar();

    // GPS — invocado desde el botón "Mi ubicación"
    window.reportarMiUbicacion = function () {
        if (!navigator.geolocation) {
            alert('Tu navegador no soporta geolocalización.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const { latitude, longitude } = pos.coords;
                map.setView([latitude, longitude], 17);
                if (userMarker) {
                    userMarker.setLatLng([latitude, longitude]);
                } else {
                    userMarker = L.circleMarker([latitude, longitude], {
                        radius: 9, fillColor: '#2563eb', color: '#fff', weight: 3, fillOpacity: 0.95,
                    }).addTo(map).bindPopup('Tu ubicación').openPopup();
                }
            },
            () => alert('No se pudo obtener tu ubicación. Verifica los permisos del navegador.')
        );
    };
});
