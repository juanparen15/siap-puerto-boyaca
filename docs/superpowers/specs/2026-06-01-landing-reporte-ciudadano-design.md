# Spec: Landing Page — Reporte Ciudadano de Alumbrado Público
**Fecha:** 2026-06-01
**Proyecto:** siap-reporte-ciudadano (React standalone)
**Sistema destino:** SIAP Puerto Boyacá (Laravel + Filament)

---

## Contexto

Aplicación ciudadana independiente donde los habitantes de Puerto Boyacá pueden:
1. Ver el inventario de alumbrado público en un mapa interactivo
2. Seleccionar un elemento (luminaria, poste, reflector, etc.)
3. Reportar un daño o problema — el reporte se crea como PQRS en Filament

Cumple con el marco regulatorio **RETILAP 580.1**.

---

## Stack técnico

| Capa | Tecnología |
|---|---|
| Framework | Next.js 15 (App Router) |
| Mapa | mapcn + MapLibre GL + OpenStreetMap tiles |
| UI | shadcn/ui + Tailwind CSS v4 |
| Animaciones | motion (framer-motion v12) |
| Lenguaje | TypeScript |
| Deploy | Vercel / servidor propio (static export posible) |

---

## Estructura del proyecto

```
siap-reporte-ciudadano/
├── app/
│   ├── layout.tsx              # Font Geist, metadata, providers
│   ├── page.tsx                # Hero + Mapa + Footer
│   └── api/
│       └── pqrs/
│           └── route.ts        # Proxy POST → Laravel /api/pqrs
├── components/
│   ├── ui/                     # shadcn/ui generados
│   ├── hero-section.tsx        # Hero con AnimatedRoadmap adaptado
│   ├── mapa-reporte.tsx        # Mapa principal con mapcn
│   ├── elemento-popup.tsx      # Popup al hacer clic en marker
│   └── form-reporte.tsx        # Sheet con formulario de reporte
├── lib/
│   ├── api.ts                  # GET /api/mapa/elementos, POST /api/pqrs
│   └── utils.ts                # cn() + helpers
└── types/
    └── index.ts                # ElementoAlumbrado, Reporte, TipoProblema
```

---

## Página única — Layout

### Bloque 1: Hero (`~100vh`)
- Badge: `RETILAP 580.1 · Municipio de Puerto Boyacá`
- Título principal: `"Reporta daños en el alumbrado público de tu municipio."`
- Subtítulo: instrucciones breves (3 pasos: seleccionar elemento → describir problema → enviar)
- CTA primario: `"Ver mapa de elementos"` (scroll suave al bloque 2)
- CTA secundario: `"Consultar mi reporte"` (link externo al sistema existente)
- Fondo: `AnimatedRoadmap` adaptado — mapa oscuro con puntos animados del inventario (posiciones demo) y SVG path conectándolos con motion

### Bloque 2: Mapa interactivo (core de la app)
- Subtítulo: `"Selecciona un elemento en el mapa para reportar un problema"`
- Buscador de dirección (geocoding OSM Nominatim)
- Filtro por tipo de elemento
- Mapa MapLibre GL con OSM tiles
- Markers del inventario con colores por estado:
  - 🟢 Verde: operativa, sin PQRS abiertas
  - 🟡 Amarillo: PQRS radicada o en_proceso activa
  - 🔴 Rojo: múltiples PQRS o elemento no_operativo
  - ⚫ Gris: desinstalada
- Clustering automático cuando hay muchos puntos juntos

### Bloque 3: Footer mínimo
- Logo Alcaldía + texto: `Alcaldía de Puerto Boyacá · SIAP · RETILAP 580.1`

---

## Flujo de usuario

```
1. Ciudadano abre la app
2. Ve hero con mapa de fondo animado
3. Hace clic en "Ver mapa" → scroll al mapa
4. Hace clic en un marker del inventario
5. Popup muestra info del elemento:
   - Rótulo (#APB-0342), tipo, dirección, estado, marca/potencia
   - Botón "Reportar problema →"
6. Sheet lateral se abre con formulario
7. Selecciona tipo de problema (Select)
8. Escribe descripción libre (Textarea)
9. Opcionalmente sube foto (file input)
10. Opcionalmente provee sus datos (nombre, cédula, teléfono)
11. Clic en "Enviar reporte"
12. Dialog de confirmación muestra radicado generado
13. Ciudadano puede consultar estado con el radicado en el sistema existente
```

---

## Tipos de problema (RETILAP 580.1)

| Valor | Label |
|---|---|
| `luminaria_apagada` | Luminaria apagada |
| `luminaria_intermitente` | Luminaria intermitente |
| `poste_danado` | Poste dañado / inclinado |
| `cable_expuesto` | Cable expuesto / riesgo eléctrico |
| `vandalismo` | Luminaria vandalizada |
| `otro` | Otro |

---

## API Laravel — GET (existente)

```
GET /api/mapa/elementos
Query params: tipo, estado, clasificacion, sw_lat, ne_lat, sw_lng, ne_lng
Respuesta: id, tipo, rotulo, estado, clasificacion, latitud, longitud, marca, potencia_w

Adición requerida: campo `pqrs_activas` (integer) en la respuesta
→ COUNT de PQRS con estado IN ('radicada', 'en_proceso') para ese elemento
```

---

## API Laravel — POST (nueva)

```
POST /api/pqrs
Content-Type: application/json

Body:
{
  "elemento_id": 342,
  "latitud": 5.9731,
  "longitud": -74.5869,
  "tipo_solicitud": "queja",               // siempre "queja"
  "descripcion": "[Luminaria apagada] Descripción del ciudadano",
  "nombre_ciudadano": "Juan Pérez",        // opcional
  "numero_cedula": "12345678",             // opcional
  "telefono": "3101234567",               // opcional
  "email": "juan@email.com"               // opcional
}

Respuesta 201:
{
  "radicado": "PQRS-2026-000147",
  "estado": "radicada",
  "mensaje": "Su reporte fue radicado exitosamente."
}
```

El `radicado` se genera automáticamente en Laravel (lógica existente).

---

## Variables de entorno

```env
NEXT_PUBLIC_API_URL=https://siap.puertoboy.gov.co
NEXT_PUBLIC_MAP_CENTER_LAT=5.9731
NEXT_PUBLIC_MAP_CENTER_LNG=-74.5869
NEXT_PUBLIC_MAP_ZOOM=14
```

---

## Paleta de colores

```
Primary:    #1a56db  (azul institucional)
Background: #0f172a  (hero oscuro)
Surface:    #ffffff
Success:    #16a34a  (marker sin reportes)
Warning:    #ca8a04  (marker con reporte activo)
Danger:     #dc2626  (marker crítico / no_operativo)
Muted:      #64748b
```

---

## Componentes shadcn/ui requeridos

`Button`, `Sheet`, `Dialog`, `Form`, `Input`, `Textarea`, `Select`, `Badge`, `Separator`, `Skeleton`

---

## Cambios requeridos en Laravel

1. **`GET /api/mapa/elementos`** — agregar campo `pqrs_activas` al select del controlador existente (`MapaController`)
2. **`POST /api/pqrs`** — nuevo endpoint en `routes/api.php` con validación y creación del modelo
3. **CORS** — permitir el origen de la app React en `config/cors.php`

---

## Fuera de alcance

- Autenticación de ciudadanos
- Subida de fotos al servidor (se deja para fase 2)
- Notificaciones por email/SMS al ciudadano
- Panel de seguimiento de PQRS dentro de la React app
