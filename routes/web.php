<?php

use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\WorkoutPresetController;
use App\Http\Controllers\WorkoutTypeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return Redirect::route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->name('dashboard');
    Route::get('/workout-presets', [WorkoutPresetController::class, 'index'])->name('workouts.presets');
    Route::get('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'show'])->name('workouts.presets.show');
    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises');

    Route::get('/backoffice/dashboard', function () {return Inertia::render('Backoffice/Dashboard');})->name('backoffice.dashboard');

    Route::get('/backoffice/body-parts', [BodyPartController::class, 'index'])->name('backoffice.bodyParts.index');
    Route::get('/backoffice/body-parts/create', [BodyPartController::class, 'create'])->name('backoffice.bodyParts.create');
    Route::post('/backoffice/body-parts', [BodyPartController::class, 'store']);
    Route::get('/backoffice/body-parts/{bodyPart}/edit', [BodyPartController::class, 'edit']);
    Route::put('/backoffice/body-parts/{bodyPart}', [BodyPartController::class, 'update']);
    Route::delete('/backoffice/body-parts/{bodyPart}', [BodyPartController::class, 'destroy']);

    Route::get('/backoffice/equipments', [EquipmentController::class, 'index'])->name('backoffice.equipments.index');
    Route::get('/backoffice/equipments/create', [EquipmentController::class, 'create'])->name('backoffice.equipments.create');
    Route::post('/backoffice/equipments', [EquipmentController::class, 'store']);
    Route::get('/backoffice/equipments/{equipment}/edit', [EquipmentController::class, 'edit']);
    Route::put('/backoffice/equipments/{equipment}', [EquipmentController::class, 'update']);
    Route::delete('/backoffice/equipments/{equipment}', [EquipmentController::class, 'destroy']);

    Route::get('/backoffice/workout-presets', [WorkoutPresetController::class, 'index'])->name('backoffice.workoutPresets.index');
    Route::get('/backoffice/workout-presets/create', [WorkoutPresetController::class, 'create'])->name('backoffice.workoutPresets.create');
    Route::post('/backoffice/workout-presets', [WorkoutPresetController::class, 'store']);
    Route::get('/backoffice/workout-presets/{workoutPreset}/edit', [WorkoutPresetController::class, 'edit']);
    Route::put('/backoffice/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'update']);
    Route::delete('/backoffice/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'destroy']);

    Route::get('/backoffice/workout-types', [WorkoutTypeController::class, 'index'])->name('backoffice.workoutTypes.index');
    Route::get('/backoffice/workout-types/create', [WorkoutTypeController::class, 'create'])->name('backoffice.workoutTypes.create');
    Route::post('/backoffice/workout-types', [WorkoutTypeController::class, 'store']);
    Route::get('/backoffice/workout-types/{workoutType}/edit', [WorkoutTypeController::class, 'edit']);
    Route::put('/backoffice/workout-types/{workoutType}', [WorkoutTypeController::class, 'update']);
    Route::delete('/backoffice/workout-types/{workoutType}', [WorkoutTypeController::class, 'destroy']);
});
