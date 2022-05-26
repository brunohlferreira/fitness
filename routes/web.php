<?php

use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutPresetController;
use App\Http\Controllers\WorkoutTypeController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return Redirect::route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('body-parts', BodyPartController::class)->except('show');
    Route::resource('equipments', EquipmentController::class)->except('show');
    Route::resource('workout-types', WorkoutTypeController::class)->except('show');

    Route::resources([
        'exercises' => ExerciseController::class,
        'workouts' => WorkoutController::class,
        'workout-presets' => WorkoutPresetController::class,
    ]);

    Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
    Route::controller(UserController::class)->group(function () {
        Route::get('/users/{user}/roles/edit', 'editRole')->name('users.edit-role');
        Route::put('/users/{user}/roles', 'updateRole')->name('users.update-role');
    });
});

Route::fallback(function () {
    abort(404, 'Not found');
});
