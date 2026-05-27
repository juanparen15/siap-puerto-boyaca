<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfraestructuraElemento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapaController extends Controller
{
    public function elementos(Request $request): JsonResponse
    {
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
