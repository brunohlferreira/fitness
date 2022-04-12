<?php

use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
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

    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::get('/workouts/{workout}', [WorkoutController::class, 'show']);
    Route::get('/workouts/{workout}/edit', [WorkoutController::class, 'edit']);
    Route::post('/workouts', [WorkoutController::class, 'store']);
    Route::put('/workouts/{workout}', [WorkoutController::class, 'update']);
    Route::delete('/workouts/{workout}', [WorkoutController::class, 'destroy']);

    Route::get('/workout-presets', [WorkoutPresetController::class, 'index'])->name('workouts.presets');
    Route::get('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'show'])->name('workouts.presets.show');

    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    Route::get('/body-parts', [BodyPartController::class, 'index'])->name('bodyParts.index');
    Route::get('/body-parts/create', [BodyPartController::class, 'create'])->name('bodyParts.create');
    Route::get('/body-parts/{bodyPart}/edit', [BodyPartController::class, 'edit']);
    Route::post('/body-parts', [BodyPartController::class, 'store']);
    Route::put('/body-parts/{bodyPart}', [BodyPartController::class, 'update']);
    Route::delete('/body-parts/{bodyPart}', [BodyPartController::class, 'destroy']);

    Route::get('/equipments', [EquipmentController::class, 'index'])->name('equipments.index');
    Route::get('/equipments/create', [EquipmentController::class, 'create'])->name('equipments.create');
    Route::get('/equipments/{equipment}/edit', [EquipmentController::class, 'edit']);
    Route::post('/equipments', [EquipmentController::class, 'store']);
    Route::put('/equipments/{equipment}', [EquipmentController::class, 'update']);
    Route::delete('/equipments/{equipment}', [EquipmentController::class, 'destroy']);

    Route::get('/workout-presets', [WorkoutPresetController::class, 'index'])->name('workoutPresets.index');
    Route::get('/workout-presets/create', [WorkoutPresetController::class, 'create'])->name('workoutPresets.create');
    Route::get('/workout-presets/{workoutPreset}/edit', [WorkoutPresetController::class, 'edit']);
    Route::post('/workout-presets', [WorkoutPresetController::class, 'store']);
    Route::put('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'update']);
    Route::delete('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'destroy']);

    Route::get('/workout-types', [WorkoutTypeController::class, 'index'])->name('workoutTypes.index');
    Route::get('/workout-types/create', [WorkoutTypeController::class, 'create'])->name('workoutTypes.create');
    Route::get('/workout-types/{workoutType}/edit', [WorkoutTypeController::class, 'edit']);
    Route::post('/workout-types', [WorkoutTypeController::class, 'store']);
    Route::put('/workout-types/{workoutType}', [WorkoutTypeController::class, 'update']);
    Route::delete('/workout-types/{workoutType}', [WorkoutTypeController::class, 'destroy']);
});
