<?php

use App\Http\Controllers\BodyPartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ExerciseModalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
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

    Route::get('/exercises/modal', [ExerciseModalController::class, 'index'])->name('exercises-modal.index');

    Route::resources([
        'exercises' => ExerciseController::class,
        'workouts' => WorkoutController::class,
        'workout-presets' => WorkoutPresetController::class,
    ]);

    Route::resource('users', UserController::class)->only(['index', 'create', 'store']);

    Route::controller(UserRoleController::class)->group(function () {
        Route::get('/users/{user}/roles/edit', 'edit')->name('user-roles.edit');
        Route::put('/users/{user}/roles', 'update')->name('user-roles.update');
    });
});

Route::fallback(function () {
    abort(404, 'Not found');
});
