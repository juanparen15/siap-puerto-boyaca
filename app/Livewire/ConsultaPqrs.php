<?php

namespace App\Livewire;

use App\Models\Pqrs;
use Livewire\Component;

class ConsultaPqrs extends Component
{
    public string $busqueda = '';
    public string $tipoBusqueda = 'radicado'; // 'radicado' | 'cedula'
    public ?Pqrs $pqrs = null;
    public string $error = '';

    public function consultar(): void
    {
        $this->error = '';
        $this->pqrs = null;

        $query = Pqrs::with(['historial.usuario', 'elemento']);

        $this->pqrs = $this->tipoBusqueda === 'radicado'
            ? $query->where('radicado', $this->busqueda)->first()
            : $query->where('numero_cedula', $this->busqueda)->latest()->first();

        if (!$this->pqrs) {
            $this->error = 'No se encontró ningún PQRS con los datos ingresados.';
        }
    }

    public function render()
    {
        return view('livewire.consulta-pqrs')
            ->extends('public.layouts.app')
            ->section('content');
    }
}
