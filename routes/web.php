<?php

use App\Livewire\ConsultaPqrs;
use App\Livewire\FormularioPqrs;
use App\Livewire\MapaPublico;
use App\Livewire\ReporteCiudadano;
use App\Livewire\ReportesPublicos;
use Illuminate\Support\Facades\Route;

// Inicio = herramienta de reporte ciudadano sobre el mapa
Route::get('/', ReporteCiudadano::class)->name('landing');

Route::get('/mapa', MapaPublico::class)->name('mapa');
Route::get('/reportes', ReportesPublicos::class)->name('reportes');
Route::get('/pqrs/consultar', ConsultaPqrs::class)->name('pqrs.consultar');

// Compatibilidad: /reportar quedó integrado en el inicio
Route::redirect('/reportar', '/')->name('reportar');

// Radicar PQRS (sin throttle en la vista; el control anti-spam del envío
// se maneja dentro del componente Livewire).
Route::get('/pqrs', FormularioPqrs::class)->name('pqrs');
