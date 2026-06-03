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
import { animate } from "motion";
import { CountUp } from "countup.js";

// ─── Contadores ───────────────────────────────────────────────────────────────
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

// ─── Hero — palabra rotante (animated-hero, con motion spring) ──────────────────
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

// ─── Mapa MapLibre (estilo mapcn-marker-popup) ──────────────────────────────────
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
  if (!document.getElementById("landing-map")) return;

  const map = new maplibregl.Map({
    container: "landing-map",
    style: OSM_STYLE,
    center: [PUERTO_BOYACA.lng, PUERTO_BOYACA.lat],
    zoom: PUERTO_BOYACA.zoom,
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

  const popup = new maplibregl.Popup({ closeButton: true, maxWidth: "260px", offset: 14 });
  let abortController = null;

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
      popup
        .setLngLat(e.features[0].geometry.coordinates.slice())
        .setHTML(buildPopupHtml(e.features[0].properties))
        .addTo(map);
    });

    cargar();
  });

  map.on("moveend", cargar);
}

document.addEventListener("DOMContentLoaded", () => {
  initCounters();
  initHeroRotator();
  initMap();
});
