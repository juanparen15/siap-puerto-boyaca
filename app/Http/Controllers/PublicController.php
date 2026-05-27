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
                'total' => (int) InfraestructuraElemento::count(),
                'operativos' => (int) InfraestructuraElemento::where('estado', 'operativa')->count(),
                'no_operativos' => (int) InfraestructuraElemento::where('estado', 'no_operativa')->count(),
                'pqrs_activos' => (int) Pqrs::whereIn('estado', ['radicada', 'en_proceso'])->count(),
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

    public function pqrs(): \Illuminate\View\View
    {
        return view('public.pqrs-stub');
    }

    public function pqrsConsultar(): \Illuminate\View\View
    {
        return view('public.pqrs-consultar-stub');
    }
}
