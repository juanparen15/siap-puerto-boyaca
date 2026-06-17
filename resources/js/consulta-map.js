import { maplibregl, OSM_STYLE } from "./maplibre-common.js";

// Mapa de una sola ubicación en la consulta de PQRS. El contenedor #pqrs-map
// aparece tras la búsqueda (re-render de Livewire), por eso intentamos
// inicializar en varios momentos y marcamos el elemento para no duplicar.
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
  new maplibregl.Marker({ color: "#3366CC" }).setLngLat([lng, lat]).addTo(map);
  setTimeout(() => map.resize(), 200);
}

document.addEventListener("DOMContentLoaded", initConsultaMap);
document.addEventListener("livewire:navigated", initConsultaMap);

// Livewire 3: reintentar tras cada actualización del DOM (resultado de búsqueda)
document.addEventListener("livewire:init", () => {
  if (!window.Livewire) return;
  window.Livewire.hook("morph.updated", () => setTimeout(initConsultaMap, 50));
  window.Livewire.hook("commit", ({ respond }) => respond(() => setTimeout(initConsultaMap, 80)));
});
