<?php

use App\Http\Controllers\PacijentController;
use App\Http\Controllers\ZdravstveniKartonController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('login');
})->name('login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('dashboard');
})->name('dashboard');

Route::post('/login', [AuthController::class, 'login']);
/* 
// Rute za kartone (pravljena u okviru pacijenta)
Route::get('kartoni/create/{pacijent}', [ZdravstveniKartonController::class, 'create'])->name('kartoni.create');
Route::post('kartoni', [ZdravstveniKartonController::class, 'store'])->name('kartoni.store');
Route::get('kartoni/{karton}', [ZdravstveniKartonController::class, 'show'])->name('kartoni.show');
Route::get('kartoni/{karton}/edit', [ZdravstveniKartonController::class, 'edit'])->name('kartoni.edit');
Route::put('kartoni/{karton}', [ZdravstveniKartonController::class, 'update'])->name('kartoni.update');
Route::delete('kartoni/{karton}', [ZdravstveniKartonController::class, 'destroy'])->name('kartoni.destroy');  */

require __DIR__.'/settings.php';
