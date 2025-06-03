<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacijentController;
use App\Http\Controllers\ZdravstveniKartonController;

// Test ruta (opciono)
Route::get('/ping', function () {
    return response()->json(['message' => 'API radi!']);
});

// RESTful rute za pacijente
Route::apiResource('pacijenti', PacijentController::class);

// RESTful rute za zdravstvene kartone
Route::apiResource('kartoni', ZdravstveniKartonController::class);

use App\Http\Controllers\AuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Ostale zaštićene rute...
});