<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseModalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->filled('search')) {
            return [];
        }

        return Exercise::query()
            ->select('id', 'name')
            ->where('name', 'like', "%{$request->input('search')}%")
            ->get();
    }
}
