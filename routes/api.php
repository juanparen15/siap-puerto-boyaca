<?php

use App\Http\Controllers\Api\MapaController;
use Illuminate\Support\Facades\Route;

Route::get('/mapa/elementos', [MapaController::class, 'elementos']);
