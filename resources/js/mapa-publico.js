import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

function escHtml(str) {
    return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

window.mapaPublico = function () {
    return {
        map: null,
        markersLayer: null,
        filtros: { tipo: '', estado: '', clasificacion: '' },

        colores: {
            operativa: '#16a34a',
            no_operativa: '#dc2626',
            desinstalada: '#6b7280',
        },

        init() {
            this._abortController = null;
            this.map = L.map('mapa-publico').setView([5.977, -74.579], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(this.map);

            this.markersLayer = L.layerGroup().addTo(this.map);

            // Load elements when map moves/zooms
            this.map.on('moveend zoomend', () => this.cargarElementos());

            // Initial load
            this.cargarElementos();
        },

        cargarElementos() {
            if (this._abortController) {
                this._abortController.abort();
            }
            this._abortController = new AbortController();
            const signal = this._abortController.signal;

            const bounds = this.map.getBounds();
            const params = new URLSearchParams({
                sw_lat: bounds.getSouth(),
                sw_lng: bounds.getWest(),
                ne_lat: bounds.getNorth(),
                ne_lng: bounds.getEast(),
                ...Object.fromEntries(
                    Object.entries(this.filtros).filter(([, v]) => v !== '')
                ),
            });

            fetch('/api/mapa/elementos?' + params, { signal })
                .then(r => r.json())
                .then(elementos => {
                    this.markersLayer.clearLayers();

                    elementos.forEach(el => {
                        const color = this.colores[el.estado] || '#6b7280';
                        const marker = L.circleMarker(
                            [parseFloat(el.latitud), parseFloat(el.longitud)],
                            {
                                radius: 6,
                                fillColor: color,
                                color: '#ffffff',
                                weight: 1.5,
                                fillOpacity: 0.9,
                            }
                        );

                        marker.bindPopup(this.buildPopup(el), { maxWidth: 220 });
                        this.markersLayer.addLayer(marker);
                    });

                    const contador = document.getElementById('contador-elementos');
                    if (contador) contador.textContent = elementos.length;
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        console.error('Error cargando elementos:', err);
                    }
                });
        },

        buildPopup(el) {
            const tipoLabel = {
                luminaria: 'Luminaria',
                poste: 'Poste',
                reflector: 'Reflector',
                sendero_peatonal: 'Sendero Peatonal',
                campo_deportivo: 'Campo Deportivo',
                luminaria_parque: 'Luminaria de Parque',
            };
            const estadoBadge = {
                operativa:    { bg: '#dcfce7', color: '#166534', label: 'Operativa' },
                no_operativa: { bg: '#fee2e2', color: '#991b1b', label: 'No Operativa' },
                desinstalada: { bg: '#f3f4f6', color: '#4b5563', label: 'Desinstalada' },
            };
            const badge  = estadoBadge[el.estado] || { bg: '#f3f4f6', color: '#4b5563', label: escHtml(el.estado) };
            const rotulo = escHtml(el.rotulo || '');
            const tipo   = escHtml(tipoLabel[el.tipo] || el.tipo || '');
            const svgFlag = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>`;

            return `
                <div style="font-family:system-ui,sans-serif;padding:10px 12px;min-width:175px;">
                    <p style="font-weight:700;font-size:13px;color:#1B6B2F;margin:0 0 3px;line-height:1.3;">
                        ${rotulo || 'Elemento #' + parseInt(el.id, 10)}
                    </p>
                    <p style="font-size:11px;color:#6b7280;margin:0 0 8px;">
                        ${tipo}${el.potencia_w ? ' · ' + escHtml(el.potencia_w) + ' W' : ''}
                    </p>
                    <span style="display:inline-block;font-size:10px;font-weight:600;padding:2px 9px;border-radius:9999px;background:${badge.bg};color:${badge.color};margin-bottom:10px;">
                        ${badge.label}
                    </span>
                    <a href="/pqrs?elemento_id=${parseInt(el.id, 10)}"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;background:#1B6B2F;color:#fff;padding:8px 14px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:600;width:100%;box-sizing:border-box;">
                        ${svgFlag} Reportar problema
                    </a>
                </div>`;
        },

        actualizarFiltros(nuevosFiltros) {
            this.filtros = nuevosFiltros;
            this.cargarElementos();
        },
    };
};
