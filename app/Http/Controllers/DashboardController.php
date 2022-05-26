<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'workouts' => [
                'total' => Workout::query()->where('created_by', Auth::user()->id)->count(),
            ],
        ]);
    }
}
