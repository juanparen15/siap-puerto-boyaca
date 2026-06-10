import {
  maplibregl,
  OSM_STYLE,
  PUERTO_BOYACA,
  TIPO_LABEL,
  escHtml,
  colorElemento,
  toFeatureCollection,
  addElementosLayers,
  wireClusterBehavior,
  boundsParams,
} from "./maplibre-common.js";

// Factory Alpine del paso 2 del formulario PQRS. Muestra TODO el inventario
// (igual que /mapa) y permite seleccionar el punto a reportar:
//   x-data="mapaPqrs({ lat, lng, hasPreciseLocation, elementoId })" x-init="init()"
window.mapaPqrs = function ({ lat, lng, hasPreciseLocation, elementoId }) {
  return {
    map: null,
    popup: null,
    _abort: null,
    _userMarker: null,
    _drewSel: false,
    selectedId: elementoId || null,

    init() {
      const center =
        hasPreciseLocation && lat && lng
          ? [Number(lng), Number(lat)]
          : [PUERTO_BOYACA.lng, PUERTO_BOYACA.lat];

      this.map = new maplibregl.Map({
        container: this.$el,
        style: OSM_STYLE,
        center,
        zoom: hasPreciseLocation ? 16 : 14,
        attributionControl: { compact: true },
      });

      this.map.addControl(new maplibregl.NavigationControl(), "top-right");
      const geolocate = new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true,
        showUserLocation: true,
      });
      this.map.addControl(geolocate, "top-right");

      // Botón externo "Mi ubicación"
      window.siapMiUbicacion = () => {
        if (!("geolocation" in navigator)) {
          alert("Tu navegador no permite geolocalización.");
          return;
        }
        const inseguro =
          location.protocol !== "https:" &&
          location.hostname !== "localhost" &&
          location.hostname !== "127.0.0.1";
        navigator.geolocation.getCurrentPosition(
          (pos) => {
            const { longitude, latitude } = pos.coords;
            this.map.flyTo({ center: [longitude, latitude], zoom: 16, essential: true });
            if (this._userMarker) this._userMarker.remove();
            this._userMarker = new maplibregl.Marker({ color: "#3366CC" })
              .setLngLat([longitude, latitude])
              .addTo(this.map);
          },
          (err) => {
            let msg = "No se pudo obtener tu ubicación.";
            if (err.code === 1) msg = "Permiso de ubicación denegado. Actívalo en el navegador.";
            else if (err.code === 3) msg = "La ubicación tardó demasiado. Intenta de nuevo.";
            if (inseguro) msg = "La ubicación requiere una conexión segura (HTTPS). En el servidor con certificado funcionará.";
            alert(msg);
          },
          { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
      };

      this.popup = new maplibregl.Popup({ closeButton: true, maxWidth: "260px", offset: 14 });

      this.map.on("load", () => {
        addElementosLayers(this.map);
        wireClusterBehavior(this.map);

        // Anillo de resaltado del punto seleccionado (debajo de "punto")
        this.map.addSource("seleccionado", {
          type: "geojson",
          data: { type: "FeatureCollection", features: [] },
        });
        this.map.addLayer(
          {
            id: "seleccionado-ring",
            type: "circle",
            source: "seleccionado",
            paint: {
              "circle-radius": 14,
              "circle-color": "#3366CC",
              "circle-opacity": 0.2,
              "circle-stroke-width": 3,
              "circle-stroke-color": "#3366CC",
            },
          },
          "punto"
        );

        // Punto preseleccionado (viene de /mapa): resaltarlo de una vez
        if (this.selectedId && hasPreciseLocation && lat && lng) {
          this.dibujarSeleccion(Number(lng), Number(lat));
          this._drewSel = true;
        }

        this.map.on("click", "punto", (e) => {
          const props = e.features[0].properties;
          const coords = e.features[0].geometry.coordinates.slice();
          this.popup.setLngLat(coords).setDOMContent(this.popupNode(props, coords)).addTo(this.map);
        });

        this.cargar();
      });

      this.map.on("moveend", () => this.cargar());
    },

    popupNode(el, coords) {
      const color = colorElemento(el);
      const estado =
        el.estado === "operativa" ? "Operativa"
        : el.estado === "no_operativa" ? "Fuera de servicio"
        : "Desinstalada";
      const tipo = escHtml(TIPO_LABEL[el.tipo] || el.tipo || "");
      const rotulo = escHtml(el.rotulo || "Elemento #" + parseInt(el.id, 10));
      const esSel = this.selectedId === parseInt(el.id, 10);

      const node = document.createElement("div");
      node.style.fontFamily = "system-ui, sans-serif";
      node.style.minWidth = "210px";
      node.innerHTML = `
        <div style="padding:12px 13px;">
          <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
            <span style="width:9px;height:9px;border-radius:9999px;background:${color};display:inline-block;"></span>
            <strong style="font-size:13px;color:#0f172a;">${rotulo}</strong>
          </div>
          <p style="margin:0 0 3px;font-size:12px;color:#475569;">${tipo}${el.potencia_w ? " · " + escHtml(String(el.potencia_w)) + " W" : ""}</p>
          <p style="margin:0 0 10px;font-size:11px;color:#64748b;">Estado: ${estado}${el.pqrs_activas > 0 ? " · " + el.pqrs_activas + " reporte(s)" : ""}</p>
          <button type="button" style="width:100%;background:${esSel ? "#16a34a" : "#3366CC"};color:#fff;border:none;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;">
            ${esSel ? "✓ Punto seleccionado" : "Seleccionar este punto"}
          </button>
        </div>`;

      node.querySelector("button").addEventListener("click", () => {
        this.seleccionar(el, coords);
        this.popup.remove();
      });
      return node;
    },

    seleccionar(el, coords) {
      this.selectedId = parseInt(el.id, 10);
      const lng = coords[0];
      const lat = coords[1];
      this.dibujarSeleccion(lng, lat);
      this.map.flyTo({ center: [lng, lat], zoom: Math.max(this.map.getZoom(), 16), essential: true });
      // Una sola llamada al componente Livewire
      this.$wire.seleccionarPunto(this.selectedId, lat, lng);
    },

    dibujarSeleccion(lng, lat) {
      const src = this.map.getSource("seleccionado");
      if (src) {
        src.setData({
          type: "FeatureCollection",
          features: [{ type: "Feature", geometry: { type: "Point", coordinates: [lng, lat] }, properties: {} }],
        });
      }
    },

    cargar() {
      this._abort?.abort();
      this._abort = new AbortController();
      fetch("/api/mapa/elementos?" + boundsParams(this.map), { signal: this._abort.signal })
        .then((r) => r.json())
        .then((els) => {
          const src = this.map.getSource("elementos");
          if (src) src.setData(toFeatureCollection(els));
          // resaltar preseleccionado si aún no se dibujó
          if (this.selectedId && !this._drewSel) {
            const f = els.find((e) => parseInt(e.id, 10) === this.selectedId);
            if (f && f.latitud && f.longitud) {
              this.dibujarSeleccion(Number(f.longitud), Number(f.latitud));
              this._drewSel = true;
            }
          }
        })
        .catch((err) => {
          if (err.name !== "AbortError") console.error("Error cargando elementos:", err);
        });
    },
  };
};
