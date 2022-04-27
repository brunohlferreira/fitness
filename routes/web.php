<?php

use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
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

    Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');
    Route::get('/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
    Route::get('/exercises/{exercise}/edit', [ExerciseController::class, 'edit']);
    Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::put('/exercises/{exercise}', [ExerciseController::class, 'update']);
    Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy']);

    Route::get('/workout-types', [WorkoutTypeController::class, 'index'])->name('workoutTypes.index');
    Route::get('/workout-types/create', [WorkoutTypeController::class, 'create'])->name('workoutTypes.create');
    Route::get('/workout-types/{workoutType}/edit', [WorkoutTypeController::class, 'edit']);
    Route::post('/workout-types', [WorkoutTypeController::class, 'store']);
    Route::put('/workout-types/{workoutType}', [WorkoutTypeController::class, 'update']);
    Route::delete('/workout-types/{workoutType}', [WorkoutTypeController::class, 'destroy']);

    Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
    Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
    Route::get('/workouts/{workout}/edit', [WorkoutController::class, 'edit']);
    Route::get('/workouts/{workout}', [WorkoutController::class, 'show']);
    Route::post('/workouts/{workout}/repeat', [WorkoutController::class, 'repeat']);
    Route::post('/workouts', [WorkoutController::class, 'store']);
    Route::put('/workouts/{workout}', [WorkoutController::class, 'update']);
    Route::delete('/workouts/{workout}', [WorkoutController::class, 'destroy']);

    Route::get('/workout-presets', [WorkoutPresetController::class, 'index'])->name('workoutPresets.index');
    Route::get('/workout-presets/create', [WorkoutPresetController::class, 'create'])->name('workoutPresets.create');
    Route::get('/workout-presets/{workoutPreset}/edit', [WorkoutPresetController::class, 'edit']);
    Route::get('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'show']);
    Route::post('/workout-presets/{workoutPreset}/repeat', [WorkoutPresetController::class, 'repeat']);
    Route::post('/workout-presets', [WorkoutPresetController::class, 'store']);
    Route::put('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'update']);
    Route::delete('/workout-presets/{workoutPreset}', [WorkoutPresetController::class, 'destroy']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}/edit-role', [UserController::class, 'editRole']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}/role', [UserController::class, 'updateRole']);

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
});
