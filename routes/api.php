<?php

use App\Http\Controllers\Api\MapaController;
use App\Http\Controllers\Api\ReporteController;
use Illuminate\Support\Facades\Route;

Route::get('/mapa/elementos', [MapaController::class, 'elementos']);

// Reporte ciudadano (app standalone) → radica como PQRS
Route::post('/pqrs', [ReporteController::class, 'store'])->middleware('throttle:10,60');
