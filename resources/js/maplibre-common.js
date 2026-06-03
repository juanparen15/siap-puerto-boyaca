import "maplibre-gl/dist/maplibre-gl.css";
import maplibregl from "maplibre-gl";

export { maplibregl };

/** Estilo raster OpenStreetMap. */
export const OSM_STYLE = {
  version: 8,
  sources: {
    osm: {
      type: "raster",
      tiles: ["https://a.tile.openstreetmap.org/{z}/{x}/{y}.png"],
      tileSize: 256,
      attribution: "© OpenStreetMap contributors",
    },
  },
  layers: [{ id: "osm", type: "raster", source: "osm" }],
};

export const PUERTO_BOYACA = { lng: -74.5869, lat: 5.9731, zoom: 14 };

export const TIPO_LABEL = {
  luminaria: "Luminaria",
  poste: "Poste",
  reflector: "Reflector",
  sendero_peatonal: "Sendero peatonal",
  campo_deportivo: "Campo deportivo",
  luminaria_parque: "Luminaria de parque",
};

export function escHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

/** Color del marcador según estado del elemento + PQRS activas. */
export function colorElemento(el) {
  if (el.estado === "desinstalada") return "#94a3b8";
  if (el.estado === "no_operativa" || el.pqrs_activas >= 2) return "#dc2626";
  if (el.pqrs_activas >= 1) return "#ca8a04";
  return "#16a34a";
}

export function toFeatureCollection(elementos) {
  return {
    type: "FeatureCollection",
    features: elementos
      .filter((e) => e.latitud && e.longitud)
      .map((e) => ({
        type: "Feature",
        geometry: {
          type: "Point",
          coordinates: [Number(e.longitud), Number(e.latitud)],
        },
        properties: { ...e, color: colorElemento(e) },
      })),
  };
}

/** Agrega source clusterizado + capas de clusters y puntos a un mapa cargado. */
export function addElementosLayers(map) {
  map.addSource("elementos", {
    type: "geojson",
    data: { type: "FeatureCollection", features: [] },
    cluster: true,
    clusterRadius: 50,
    clusterMaxZoom: 15,
  });

  map.addLayer({
    id: "clusters",
    type: "circle",
    source: "elementos",
    filter: ["has", "point_count"],
    paint: {
      "circle-color": "#1B6B2F",
      "circle-opacity": 0.85,
      "circle-radius": ["step", ["get", "point_count"], 16, 25, 22, 100, 30],
      "circle-stroke-width": 3,
      "circle-stroke-color": "#ffffff",
    },
  });
  map.addLayer({
    id: "cluster-count",
    type: "symbol",
    source: "elementos",
    filter: ["has", "point_count"],
    layout: { "text-field": ["get", "point_count_abbreviated"], "text-size": 13 },
    paint: { "text-color": "#ffffff" },
  });
  map.addLayer({
    id: "punto",
    type: "circle",
    source: "elementos",
    filter: ["!", ["has", "point_count"]],
    paint: {
      "circle-color": ["get", "color"],
      "circle-radius": 7,
      "circle-stroke-width": 2,
      "circle-stroke-color": "#ffffff",
    },
  });
}

/** Comportamiento estándar de clusters: zoom al hacer clic + cursor pointer. */
export function wireClusterBehavior(map) {
  map.on("click", "clusters", (e) => {
    const f = map.queryRenderedFeatures(e.point, { layers: ["clusters"] });
    const id = f[0].properties.cluster_id;
    map.getSource("elementos").getClusterExpansionZoom(id).then((zoom) => {
      map.easeTo({ center: f[0].geometry.coordinates, zoom });
    });
  });
  const cur = (c) => () => (map.getCanvas().style.cursor = c);
  map.on("mouseenter", "clusters", cur("pointer"));
  map.on("mouseleave", "clusters", cur(""));
  map.on("mouseenter", "punto", cur("pointer"));
  map.on("mouseleave", "punto", cur(""));
}

export function boundsParams(map, extra = {}) {
  const b = map.getBounds();
  return new URLSearchParams({
    sw_lat: b.getSouth(),
    sw_lng: b.getWest(),
    ne_lat: b.getNorth(),
    ne_lng: b.getEast(),
    ...extra,
  });
}
