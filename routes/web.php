<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/mapa', [PublicController::class, 'mapa'])->name('mapa');
Route::get('/reportes', [PublicController::class, 'reportes'])->name('reportes');
Route::get('/pqrs', fn() => view('public.pqrs-stub'))->name('pqrs');
Route::get('/pqrs/consultar', fn() => view('public.pqrs-consultar-stub'))->name('pqrs.consultar');
