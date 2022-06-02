<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutRequest;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\User;
use App\Models\Workout;
use App\Services\WorkoutService;
use App\Services\WorkoutTypeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WorkoutController extends Controller
{
    private $workoutService;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(WorkoutService $workoutService)
    {
        $this->workoutService = $workoutService;
        $this->authorizeResource(Workout::class, 'workout');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Workouts/Index', [
            'workouts' => WorkoutResource::collection(
                Workout::query()
                    ->select('id', 'name', 'date')
                    ->where('created_by', Auth::user()->id)
                    ->orderBy('date', 'desc')
                    ->paginate(15)
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, WorkoutTypeService $workoutType)
    {
        $workout = null;

        if ($request->input('workout')) {
            $workout = $this->workoutService->createFromWorkout(intval($request->input('workout')));
        } elseif ($request->input('workoutPreset')) {
            $workout = $this->workoutService->createFromWorkoutPreset(intval($request->input('workoutPreset')));
        }

        return Inertia::render('Workouts/Create', [
            'workout' => new WorkoutResource(
                $workout
            ),
            'workoutTypes' => WorkoutTypeResource::collection(
                $workoutType->getAll()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->workoutService->store($request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workouts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout)
    {
        return Inertia::render('Workouts/Show', [
            'workout' => new WorkoutResource(
                $this->workoutService->show($workout)
            ),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout, WorkoutTypeService $workoutType)
    {
        return Inertia::render('Workouts/Edit', [
            'workout' => new WorkoutResource(
                $this->workoutService->edit($workout)
            ),
            'workoutTypes' => WorkoutTypeResource::collection(
                $workoutType->getAll()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutRequest  $request
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function update(WorkoutRequest $request, Workout $workout)
    {
        DB::beginTransaction();
        try {
            $this->workoutService->update($workout, $request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workouts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workout $workout)
    {
        DB::beginTransaction();
        try {
            $workout->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            abort(500, 'Something went wrong. Please try again later.');
        }

        return response()->noContent();
    }
}
