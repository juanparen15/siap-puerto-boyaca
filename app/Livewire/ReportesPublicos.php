<?php
namespace App\Livewire;

use App\Models\FacturacionPeriodo;
use App\Models\InfraestructuraElemento;
use App\Models\Pqrs;
use App\Models\Recaudo;
use Livewire\Component;

class ReportesPublicos extends Component
{
    public function render(): \Illuminate\View\View
    {
        $stats = [
            'total_elementos' => InfraestructuraElemento::count(),
            'por_tipo' => InfraestructuraElemento::selectRaw('tipo, count(*) as total')
                ->groupBy('tipo')->pluck('total', 'tipo'),
            'por_estado' => InfraestructuraElemento::selectRaw('estado, count(*) as total')
                ->groupBy('estado')->pluck('total', 'estado'),
            'pqrs_por_estado' => Pqrs::selectRaw('estado, count(*) as total')
                ->groupBy('estado')->pluck('total', 'estado'),
            'facturacion_reciente' => FacturacionPeriodo::orderBy('periodo', 'desc')->take(6)->get(),
            'recaudos_recientes' => Recaudo::orderBy('fecha_recaudo', 'desc')->take(6)->get(),
        ];

        return view('livewire.reportes-publicos', $stats)
            ->extends('public.layouts.app')
            ->section('content');
    }
}
