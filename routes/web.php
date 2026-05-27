<?php

use App\Http\Controllers\PublicController;
use App\Livewire\MapaPublico;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/mapa', MapaPublico::class)->name('mapa');
Route::get('/reportes', [PublicController::class, 'reportes'])->name('reportes');
Route::get('/pqrs', [PublicController::class, 'pqrs'])->name('pqrs');
Route::get('/pqrs/consultar', [PublicController::class, 'pqrsConsultar'])->name('pqrs.consultar');
