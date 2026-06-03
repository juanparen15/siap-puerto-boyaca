import { maplibregl, OSM_STYLE } from "./maplibre-common.js";

// Factory Alpine usado por el paso 2 del formulario PQRS:
//   x-data="mapaPqrs({ lat, lng, hasPreciseLocation })" x-init="init()"
window.mapaPqrs = function ({ lat, lng, hasPreciseLocation }) {
  return {
    map: null,
    marker: null,

    init() {
      const wire = this.$wire;
      const centerLat = hasPreciseLocation ? lat : 5.976;
      const centerLng = hasPreciseLocation ? lng : -74.594;

      this.map = new maplibregl.Map({
        container: this.$el,
        style: OSM_STYLE,
        center: [centerLng, centerLat],
        zoom: hasPreciseLocation ? 17 : 14,
        attributionControl: { compact: true },
      });
      this.map.addControl(new maplibregl.NavigationControl(), "top-right");

      const setPin = (lngLat) => {
        if (this.marker) {
          this.marker.setLngLat(lngLat);
        } else {
          this.marker = new maplibregl.Marker({ draggable: true, color: "#1B6B2F" })
            .setLngLat(lngLat)
            .addTo(this.map);
          this.marker.on("dragend", () => {
            const p = this.marker.getLngLat();
            wire.set("latitud", p.lat);
            wire.set("longitud", p.lng);
          });
        }
        wire.set("latitud", lngLat.lat ?? lngLat[1]);
        wire.set("longitud", lngLat.lng ?? lngLat[0]);
      };

      if (hasPreciseLocation) {
        this.marker = new maplibregl.Marker({ draggable: true, color: "#1B6B2F" })
          .setLngLat([lng, lat])
          .addTo(this.map);
        this.marker.on("dragend", () => {
          const p = this.marker.getLngLat();
          wire.set("latitud", p.lat);
          wire.set("longitud", p.lng);
        });
      }

      this.map.on("click", (e) => setPin(e.lngLat));
    },
  };
};
