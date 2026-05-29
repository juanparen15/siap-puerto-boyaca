<?php

use App\Http\Controllers\PublicController;
use App\Livewire\ConsultaPqrs;
use App\Livewire\FormularioPqrs;
use App\Livewire\MapaPublico;
use App\Livewire\ReportesPublicos;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/mapa', MapaPublico::class)->name('mapa');
Route::get('/reportes', ReportesPublicos::class)->name('reportes');
Route::get('/pqrs/consultar', ConsultaPqrs::class)->name('pqrs.consultar');

Route::middleware(['throttle:5,60'])->group(function () {
    Route::get('/pqrs', FormularioPqrs::class)->name('pqrs');
});
