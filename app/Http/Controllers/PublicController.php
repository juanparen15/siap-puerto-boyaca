<?php

namespace App\Http\Controllers;

use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function landing(): View
    {
        $stats = cache()->remember('landing_stats', now()->addMinutes(10), function () {
            return [
                'total' => InfraestructuraElemento::count(),
                'operativos' => InfraestructuraElemento::where('estado', 'operativa')->count(),
                'no_operativos' => InfraestructuraElemento::where('estado', 'no_operativa')->count(),
                'pqrs_activos' => Pqrs::whereIn('estado', ['radicada', 'en_proceso'])->count(),
            ];
        });
        return view('public.landing', compact('stats'));
    }

    public function mapa(): View
    {
        return view('public.mapa');
    }

    public function reportes(): View
    {
        return view('public.reportes');
    }
}
