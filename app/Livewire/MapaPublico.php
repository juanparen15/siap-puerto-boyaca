<?php

namespace App\Livewire;

use Livewire\Component;

class MapaPublico extends Component
{
    public string $filtroTipo = '';
    public string $filtroEstado = '';
    public string $filtroClasificacion = '';
    public ?int $elementoSeleccionadoId = null;

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'filtro')) {
            $this->dispatch('filtros-changed',
                tipo: $this->filtroTipo,
                estado: $this->filtroEstado,
                clasificacion: $this->filtroClasificacion,
            );
        }
    }

    public function render()
    {
        return view('livewire.mapa-publico')
            ->extends('public.layouts.app')
            ->section('content');
    }
}
