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

function buildPopup(el) {
  const estadoBadge = {
    operativa: { bg: "#dcfce7", color: "#166534", label: "Operativa" },
    no_operativa: { bg: "#fee2e2", color: "#991b1b", label: "No Operativa" },
    desinstalada: { bg: "#f3f4f6", color: "#4b5563", label: "Desinstalada" },
  };
  const badge = estadoBadge[el.estado] || { bg: "#f3f4f6", color: "#4b5563", label: escHtml(el.estado) };
  const rotulo = escHtml(el.rotulo || "");
  const tipo = escHtml(TIPO_LABEL[el.tipo] || el.tipo || "");
  const svgFlag = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;display:inline-block;vertical-align:middle;flex-shrink:0;"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>`;

  return `
    <div style="font-family:system-ui,sans-serif;padding:10px 12px;min-width:175px;">
      <p style="font-weight:700;font-size:13px;color:#3366CC;margin:0 0 3px;line-height:1.3;">
        ${rotulo || "Elemento #" + parseInt(el.id, 10)}
      </p>
      <p style="font-size:11px;color:#6b7280;margin:0 0 8px;">
        ${tipo}${el.potencia_w ? " · " + escHtml(el.potencia_w) + " W" : ""}
      </p>
      <span style="display:inline-block;font-size:10px;font-weight:600;padding:2px 9px;border-radius:9999px;background:${badge.bg};color:${badge.color};margin-bottom:10px;">
        ${badge.label}
      </span>
      <a href="/pqrs?elemento_id=${parseInt(el.id, 10)}"
         style="display:flex;align-items:center;justify-content:center;gap:6px;background:#3366CC;color:#fff;padding:8px 14px;border-radius:8px;text-decoration:none;font-size:12px;font-weight:600;width:100%;box-sizing:border-box;">
        ${svgFlag} Reportar problema
      </a>
    </div>`;
}

window.mapaPublico = function () {
  return {
    map: null,
    popup: null,
    filtros: { tipo: "", estado: "", clasificacion: "" },
    _abortController: null,
    _userMarker: null,

    init() {
      this.map = new maplibregl.Map({
        container: "mapa-publico",
        style: OSM_STYLE,
        center: [PUERTO_BOYACA.lng, PUERTO_BOYACA.lat],
        zoom: 13,
        attributionControl: { compact: true },
      });

      this.map.addControl(new maplibregl.NavigationControl(), "top-right");
      const geolocate = new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true,
        showUserLocation: true,
      });
      this.map.addControl(geolocate, "top-right");

      // Botón externo "Mi ubicación": centra el mapa en el ciudadano,
      // marca su posición y carga los puntos cercanos (vía moveend).
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
              .setPopup(new maplibregl.Popup({ offset: 14 }).setHTML(
                '<div style="font-family:system-ui,sans-serif;padding:6px 8px;font-size:12px;font-weight:600;color:#3366CC;">Tu ubicación</div>'
              ))
              .addTo(this.map);
          },
          (err) => {
            let msg = "No se pudo obtener tu ubicación.";
            if (err.code === 1) msg = "Permiso de ubicación denegado. Actívalo en los ajustes del navegador.";
            else if (err.code === 3) msg = "La ubicación tardó demasiado. Intenta de nuevo.";
            if (inseguro) {
              msg = "La ubicación requiere una conexión segura (HTTPS). En el servidor con certificado funcionará; en local con http no es posible.";
            }
            alert(msg);
          },
          { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
      };

      this.popup = new maplibregl.Popup({ closeButton: true, maxWidth: "240px", offset: 14 });

      this.map.on("load", () => {
        addElementosLayers(this.map);
        wireClusterBehavior(this.map);

        this.map.on("click", "punto", (e) => {
          const props = e.features[0].properties;
          this.popup
            .setLngLat(e.features[0].geometry.coordinates.slice())
            .setHTML(buildPopup(props))
            .addTo(this.map);
        });

        this.cargarElementos();
      });

      this.map.on("moveend", () => this.cargarElementos());
    },

    cargarElementos() {
      const extra = Object.fromEntries(
        Object.entries(this.filtros).filter(([, v]) => v !== "")
      );
      this._abortController?.abort();
      this._abortController = new AbortController();

      fetch("/api/mapa/elementos?" + boundsParams(this.map, extra), {
        signal: this._abortController.signal,
      })
        .then((r) => r.json())
        .then((elementos) => {
          const src = this.map.getSource("elementos");
          if (src) src.setData(toFeatureCollection(elementos));
          const contador = document.getElementById("contador-elementos");
          if (contador) contador.textContent = elementos.length;
        })
        .catch((err) => {
          if (err.name !== "AbortError") console.error("Error cargando elementos:", err);
        });
    },

    actualizarFiltros(nuevosFiltros) {
      this.filtros = nuevosFiltros;
      this.cargarElementos();
    },
  };
};
