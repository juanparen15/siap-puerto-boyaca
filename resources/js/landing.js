import "maplibre-gl/dist/maplibre-gl.css";
import maplibregl from "maplibre-gl";
import { animate } from "motion";
import { CountUp } from "countup.js";

// ─────────────────────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────────────────────
function escHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

const TIPO_LABEL = {
  luminaria: "Luminaria",
  poste: "Poste",
  reflector: "Reflector",
  sendero_peatonal: "Sendero peatonal",
  campo_deportivo: "Campo deportivo",
  luminaria_parque: "Luminaria de parque",
};

function colorElemento(el) {
  if (el.estado === "desinstalada") return "#94a3b8";
  if (el.estado === "no_operativa" || el.pqrs_activas >= 2) return "#dc2626";
  if (el.pqrs_activas >= 1) return "#ca8a04";
  return "#16a34a";
}

// ─────────────────────────────────────────────────────────────────────────────
// Contadores
// ─────────────────────────────────────────────────────────────────────────────
function initCounters() {
  const els = document.querySelectorAll(".countup");
  if (!els.length) return;
  const obs = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          const el = e.target;
          new CountUp(el, parseInt(el.dataset.target || "0", 10), {
            duration: 2.2,
            separator: ".",
            decimal: ",",
          }).start();
          obs.unobserve(el);
        }
      });
    },
    { threshold: 0.2 }
  );
  els.forEach((el) => obs.observe(el));
}

// ─────────────────────────────────────────────────────────────────────────────
// Hero — palabra rotante (adaptado de animated-hero, con motion)
// ─────────────────────────────────────────────────────────────────────────────
function initHeroRotator() {
  const words = Array.from(document.querySelectorAll("[data-hero-word]"));
  if (!words.length) return;

  words.forEach((w, i) =>
    animate(w, { y: i === 0 ? 0 : 150, opacity: i === 0 ? 1 : 0 }, { duration: 0 })
  );

  let active = 0;
  setInterval(() => {
    active = (active + 1) % words.length;
    words.forEach((w, i) => {
      const y = i === active ? 0 : i < active ? -150 : 150;
      animate(
        w,
        { y, opacity: i === active ? 1 : 0 },
        { type: "spring", stiffness: 50, damping: 14 }
      );
    });
  }, 2200);
}

// ─────────────────────────────────────────────────────────────────────────────
// Mapa MapLibre GL (estilo mapcn-marker-popup)
// ─────────────────────────────────────────────────────────────────────────────
const OSM_STYLE = {
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

function toFeatureCollection(elementos) {
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

function buildPopupHtml(el) {
  const estadoLabel =
    el.estado === "operativa"
      ? "Operativa"
      : el.estado === "no_operativa"
        ? "Fuera de servicio"
        : "Desinstalada";
  const tipo = escHtml(TIPO_LABEL[el.tipo] || el.tipo || "");
  const rotulo = escHtml(el.rotulo || "Elemento #" + el.id);
  const color = colorElemento(el);

  return `
    <div style="font-family:system-ui,sans-serif;min-width:200px;padding:12px 13px;">
      <div style="display:flex;align-items:center;gap:7px;margin-bottom:5px;">
        <span style="width:9px;height:9px;border-radius:9999px;background:${color};display:inline-block;"></span>
        <strong style="font-size:13px;color:#0f172a;">${rotulo}</strong>
      </div>
      <p style="margin:0 0 3px;font-size:12px;color:#475569;">${tipo}${
        el.potencia_w ? " · " + escHtml(String(el.potencia_w)) + " W" : ""
      }</p>
      <p style="margin:0 0 10px;font-size:11px;color:#64748b;">Estado: ${estadoLabel}${
        el.pqrs_activas > 0 ? " · " + el.pqrs_activas + " reporte(s)" : ""
      }</p>
      <a href="/reportar" style="display:block;text-align:center;background:#1B6B2F;color:#fff;padding:8px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;">Reportar un problema</a>
    </div>`;
}

function initMap() {
  const el = document.getElementById("landing-map");
  if (!el) return;

  const map = new maplibregl.Map({
    container: "landing-map",
    style: OSM_STYLE,
    center: [-74.5869, 5.9731],
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

  const btnGps = document.getElementById("landing-gps");
  if (btnGps) btnGps.addEventListener("click", () => geolocate.trigger());

  let abortController = null;
  const popup = new maplibregl.Popup({ closeButton: true, maxWidth: "260px", offset: 14 });

  function cargar() {
    const b = map.getBounds();
    const params = new URLSearchParams({
      sw_lat: b.getSouth(),
      sw_lng: b.getWest(),
      ne_lat: b.getNorth(),
      ne_lng: b.getEast(),
    });
    abortController?.abort();
    abortController = new AbortController();
    fetch("/api/mapa/elementos?" + params, { signal: abortController.signal })
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

    map.on("click", "clusters", (e) => {
      const f = map.queryRenderedFeatures(e.point, { layers: ["clusters"] });
      const id = f[0].properties.cluster_id;
      map.getSource("elementos").getClusterExpansionZoom(id).then((zoom) => {
        map.easeTo({ center: f[0].geometry.coordinates, zoom });
      });
    });

    map.on("click", "punto", (e) => {
      const feat = e.features[0];
      const props = feat.properties;
      popup.setLngLat(feat.geometry.coordinates.slice()).setHTML(buildPopupHtml(props)).addTo(map);
    });

    const cur = (c) => () => (map.getCanvas().style.cursor = c);
    map.on("mouseenter", "punto", cur("pointer"));
    map.on("mouseleave", "punto", cur(""));
    map.on("mouseenter", "clusters", cur("pointer"));
    map.on("mouseleave", "clusters", cur(""));

    cargar();
  });

  map.on("moveend", cargar);
}

// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener("DOMContentLoaded", () => {
  initCounters();
  initHeroRotator();
  initMap();
});
