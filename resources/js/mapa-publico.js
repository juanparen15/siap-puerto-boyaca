import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

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

            fetch('/api/mapa/elementos?' + params)
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
                .catch(console.error);
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

            const rotulo = el.rotulo || 'Sin rótulo';
            const tipo = tipoLabel[el.tipo] || el.tipo;

            return `
                <div style="font-family: system-ui, sans-serif; padding: 4px;">
                    <div style="font-weight:700; font-size:14px; margin-bottom:6px;">${rotulo}</div>
                    <div style="font-size:12px; color:#555; margin-bottom:2px;">Tipo: ${tipo}</div>
                    ${el.marca ? `<div style="font-size:12px; color:#555; margin-bottom:2px;">Marca: ${el.marca}</div>` : ''}
                    ${el.potencia_w ? `<div style="font-size:12px; color:#555; margin-bottom:6px;">Potencia: ${el.potencia_w} W</div>` : ''}
                    <span style="font-size:11px; padding:2px 8px; border-radius:9999px; background:${el.estado === 'operativa' ? '#dcfce7' : el.estado === 'no_operativa' ? '#fee2e2' : '#f3f4f6'}; color:${el.estado === 'operativa' ? '#166534' : el.estado === 'no_operativa' ? '#991b1b' : '#4b5563'};">${el.estado.replace('_', ' ')}</span>
                    <div style="margin-top:10px;">
                        <a href="/pqrs?elemento_id=${el.id}"
                           style="display:block; background:#1B6B2F; color:#fff; text-align:center; padding:6px 12px; border-radius:6px; font-size:12px; text-decoration:none; font-weight:600;">
                            📋 Reportar problema
                        </a>
                    </div>
                </div>
            `;
        },

        actualizarFiltros(nuevosFiltros) {
            this.filtros = nuevosFiltros;
            this.cargarElementos();
        },
    };
};
