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

document.addEventListener("DOMContentLoaded", () => {
  const el = document.getElementById("mapa-reportar");
  if (!el) return;

  const map = new maplibregl.Map({
    container: "mapa-reportar",
    style: OSM_STYLE,
    center: [PUERTO_BOYACA.lng, PUERTO_BOYACA.lat],
    zoom: 14,
    attributionControl: { compact: true },
  });

  map.addControl(new maplibregl.NavigationControl(), "top-right");
  const geolocate = new maplibregl.GeolocateControl({
    positionOptions: { enableHighAccuracy: true },
    trackUserLocation: true,
    showUserLocation: true,
  });
  map.addControl(geolocate, "top-right");

  // Botón "Mi ubicación" del Blade
  window.reportarMiUbicacion = () => geolocate.trigger();

  const popup = new maplibregl.Popup({ closeButton: true, maxWidth: "260px", offset: 14 });
  let abortController = null;

  function buildPopupNode(elm) {
    const color = colorElemento(elm);
    const estadoLabel =
      elm.estado === "operativa"
        ? "Operativa"
        : elm.estado === "no_operativa"
          ? "Fuera de servicio"
          : "Desinstalada";
    const tipo = escHtml(TIPO_LABEL[elm.tipo] || elm.tipo || "");
    const rotulo = escHtml(elm.rotulo || "Elemento #" + elm.id);

    const node = document.createElement("div");
    node.style.fontFamily = "system-ui, sans-serif";
    node.style.minWidth = "205px";
    node.innerHTML = `
      <div style="padding:12px 13px;">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
          <span style="width:9px;height:9px;border-radius:9999px;background:${color};display:inline-block;"></span>
          <strong style="font-size:13px;color:#0f172a;">${rotulo}</strong>
        </div>
        <p style="margin:0 0 3px;font-size:12px;color:#475569;">${tipo}${
          elm.potencia_w ? " · " + escHtml(String(elm.potencia_w)) + " W" : ""
        }</p>
        <p style="margin:0 0 10px;font-size:11px;color:#64748b;">Estado: ${estadoLabel}${
          elm.pqrs_activas > 0 ? " · " + elm.pqrs_activas + " reporte(s) activo(s)" : ""
        }</p>
        <button type="button" style="width:100%;background:#1B6B2F;color:#fff;border:none;padding:9px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;">Reportar un problema</button>
      </div>`;

    node.querySelector("button").addEventListener("click", () => {
      popup.remove();
      if (window.Livewire) {
        window.Livewire.dispatch("seleccionar-elemento", {
          id: elm.id,
          tipo: elm.tipo || "",
          rotulo: elm.rotulo || "",
          lat: elm.latitud ? parseFloat(elm.latitud) : null,
          lng: elm.longitud ? parseFloat(elm.longitud) : null,
        });
      }
    });

    return node;
  }

  function cargar() {
    abortController?.abort();
    abortController = new AbortController();
    fetch("/api/mapa/elementos?" + boundsParams(map), { signal: abortController.signal })
      .then((r) => r.json())
      .then((elementos) => {
        const src = map.getSource("elementos");
        if (src) src.setData(toFeatureCollection(elementos));
      })
      .catch((err) => {
        if (err.name !== "AbortError") console.error("Error cargando mapa:", err);
      });
  }

  map.on("load", () => {
    addElementosLayers(map);
    wireClusterBehavior(map);

    map.on("click", "punto", (e) => {
      const props = e.features[0].properties;
      popup
        .setLngLat(e.features[0].geometry.coordinates.slice())
        .setDOMContent(buildPopupNode(props))
        .addTo(map);
    });

    cargar();
  });

  map.on("moveend", cargar);
});
