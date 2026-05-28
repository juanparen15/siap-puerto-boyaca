<?php

use App\Http\Controllers\PublicController;
use App\Livewire\FormularioPqrs;
use App\Livewire\MapaPublico;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/mapa', MapaPublico::class)->name('mapa');
Route::get('/reportes', [PublicController::class, 'reportes'])->name('reportes');
Route::get('/pqrs/consultar', [PublicController::class, 'pqrsConsultar'])->name('pqrs.consultar');

Route::middleware(['throttle:5,60'])->group(function () {
    Route::get('/pqrs', FormularioPqrs::class)->name('pqrs');
});
