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
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resources([
        'body-parts' => BodyPartController::class,
        'equipments' => EquipmentController::class,
        'exercises' => ExerciseController::class,
        'workout-types' => WorkoutTypeController::class,
        'workouts' => WorkoutController::class,
        'workout-presets' => WorkoutPresetController::class,
    ]);

    Route::resource('users', UserController::class)->only(['index', 'create', 'store']);
    Route::get('/users/{user}/roles/edit', [UserController::class, 'editRole']);
    Route::put('/users/{user}/roles', [UserController::class, 'updateRole']);
});
