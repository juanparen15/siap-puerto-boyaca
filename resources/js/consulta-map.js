import { maplibregl, OSM_STYLE } from "./maplibre-common.js";

// Mapa de una sola ubicación en la consulta de PQRS. El contenedor #pqrs-map
// aparece tras la búsqueda (re-render de Livewire), por eso inicializamos en
// el evento livewire:updated y marcamos el elemento para no duplicar.
function initConsultaMap() {
  const el = document.getElementById("pqrs-map");
  if (!el || el.dataset.mapInit === "1") return;

  const lat = parseFloat(el.dataset.lat);
  const lng = parseFloat(el.dataset.lng);
  if (Number.isNaN(lat) || Number.isNaN(lng)) return;

  el.dataset.mapInit = "1";

  const map = new maplibregl.Map({
    container: el,
    style: OSM_STYLE,
    center: [lng, lat],
    zoom: 16,
    attributionControl: { compact: true },
  });
  map.addControl(new maplibregl.NavigationControl(), "top-right");
  new maplibregl.Marker({ color: "#1B6B2F" }).setLngLat([lng, lat]).addTo(map);
}

document.addEventListener("DOMContentLoaded", initConsultaMap);
document.addEventListener("livewire:updated", initConsultaMap);
