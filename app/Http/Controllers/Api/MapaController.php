<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfraestructuraElemento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MapaController extends Controller
{
    public function elementos(Request $request): JsonResponse
    {
        $request->validate([
            'tipo'           => ['nullable', 'string', Rule::in(['luminaria', 'poste', 'reflector', 'sendero_peatonal', 'campo_deportivo', 'luminaria_parque'])],
            'estado'         => ['nullable', 'string', Rule::in(['operativa', 'no_operativa', 'desinstalada'])],
            'clasificacion'  => ['nullable', 'string', Rule::in(['casco_urbano', 'puerto_serviez'])],
            'sw_lat'         => ['nullable', 'numeric', 'between:-4,13'],
            'sw_lng'         => ['nullable', 'numeric', 'between:-82,-66'],
            'ne_lat'         => ['nullable', 'numeric', 'between:-4,13'],
            'ne_lng'         => ['nullable', 'numeric', 'between:-82,-66'],
        ]);

        $query = InfraestructuraElemento::select(
            'id', 'tipo', 'rotulo', 'estado', 'clasificacion',
            'latitud', 'longitud', 'marca', 'potencia_w'
        );

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('clasificacion')) {
            $query->where('clasificacion', $request->clasificacion);
        }

        // Bounding box filter — required to avoid loading all 8,110 records per request
        if ($request->filled(['sw_lat', 'sw_lng', 'ne_lat', 'ne_lng'])) {
            $query->whereBetween('latitud', [$request->sw_lat, $request->ne_lat])
                  ->whereBetween('longitud', [$request->sw_lng, $request->ne_lng]);
        }

        return response()->json(
            $query->limit(2000)->get()
        );
    }
}
