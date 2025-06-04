<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacijentController;
use App\Http\Controllers\ZdravstveniKartonController;
use App\Http\Controllers\PregledController;
use App\Http\Controllers\AuthController;
use Illuminate\Cache\RateLimiting\Limit;

// ✅ Definiši rate limiter ako nedostaje
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

// Test ruta (opciono)
Route::get('/ping', function () {
    return response()->json(['message' => 'API radi!']);
});



// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // RESTful rute za pacijente
    Route::apiResource('pacijenti', PacijentController::class);

    // RESTful rute za zdravstvene kartone
    Route::apiResource('kartoni', ZdravstveniKartonController::class);
    Route::apiResource('pregledi', PregledController::class);
});


