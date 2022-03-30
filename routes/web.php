<?php

use App\Http\Controllers\BodyPartController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return Redirect::route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/backoffice/dashboard', function () {return Inertia::render('Backoffice/Dashboard');})->name('backoffice.dashboard');
    Route::get('/backoffice/body-parts', [BodyPartController::class, 'index'])->name('backoffice.bodyParts.index');
    Route::get('/backoffice/body-parts/create', [BodyPartController::class, 'create'])->name('backoffice.bodyParts.create');
    Route::post('/backoffice/body-parts', [BodyPartController::class, 'store']);
    Route::get('/backoffice/body-parts/{bodyPart}/edit', [BodyPartController::class, 'edit']);
    Route::put('/backoffice/body-parts/{bodyPart}', [BodyPartController::class, 'update']);
    Route::delete('/backoffice/body-parts/{bodyPart}', [BodyPartController::class, 'destroy']);
});
