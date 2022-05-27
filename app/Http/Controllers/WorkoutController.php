<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\ExerciseWorkout;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutSet;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutPreset;
use App\Models\WorkoutType;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class WorkoutController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
    public function create(Request $request)
    {
        $workout = null;

        if ($request->input('workout')) {
            $workout = Workout::query()
                ->where([
                    ['id', intval($request->input('workout'))],
                    ['created_by', Auth::user()->id],
                ])
                ->first();

            if ($workout) {
                $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
                    return array_merge(
                        $exercise->only('id', 'name'),
                        ['note' => $exercise->pivot->note],
                        ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
                    );
                });

                $workout = new WorkoutResource(
                    $workout->only('id', 'name', 'description', 'level', 'time_cap', 'score', 'workout_type_id', 'workout_preset_id', 'exercises')
                );
            }
        } elseif ($request->input('workoutPreset')) {
            $workout = WorkoutPreset::query()->where('id', intval($request->input('workoutPreset')))->first();

            if ($workout) {
                $workout->score = '';
                $workout->workout_preset_id = $workout->id;
                $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
                    return array_merge(
                        $exercise->only('id', 'name'),
                        ['note' => $exercise->pivot->note],
                        ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()],
                    );
                });

                $workout = new WorkoutResource(
                    $workout->only('id', 'name', 'description', 'level', 'time_cap', 'score', 'workout_type_id', 'workout_preset_id', 'exercises')
                );
            }
        }

        return Inertia::render('Workouts/Create', [
            'workout' => $workout,
            'workoutTypes' => WorkoutTypeResource::collection(
                WorkoutType::query()->select('id', 'name', 'description')->get()
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
            $workout = Workout::create($request->validated());

            if (is_array($request->input('exercises'))) {
                foreach ($request->input('exercises') as $exerciseKey => $exercise) {
                    $exerciseWorkout = ExerciseWorkout::create([
                        'exercise_id' => $exercise['id'],
                        'workout_id' => $workout->id,
                        'position' => $exerciseKey,
                        'note' => $exercise['note'],
                    ]);

                    if (!is_array($exercise['sets'])) {
                        continue;
                    }

                    foreach ($exercise['sets'] as $setKey => $set) {
                        $newSet['repetitions'] = isset($set['repetitions']) ? intval($set['repetitions']) : 0;
                        $newSet['weight'] = isset($set['weight']) ? intval($set['weight']) : 0;
                        $newSet['distance'] = isset($set['distance']) ? floatval($set['distance']) : 0;
                        $newSet['calories'] = isset($set['calories']) ? intval($set['calories']) : 0;
                        $newSet['minutes'] = isset($set['minutes']) ? intval($set['minutes']) : 0;

                        // discard empty sets
                        if (!array_sum($newSet)) {
                            continue;
                        }

                        $newSet['exercise_workout_id'] = $exerciseWorkout->id;
                        $newSet['position'] = $setKey;
                        ExerciseWorkoutSet::create($newSet);
                    }
                }
            }

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
        $workout->workout_type_name = $workout->type->name;
        $workout->workout_type_description = $workout->type->description;
        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return Inertia::render(
            'Workouts/Show',
            [
                'workout' => new WorkoutResource(
                    $workout->only('id', 'name', 'description', 'date', 'level', 'time_cap', 'score', 'workout_type_name', 'workout_type_description', 'exercises')
                ),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function edit(Workout $workout)
    {
        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return Inertia::render(
            'Workouts/Edit',
            [
                'workout' => new WorkoutResource(
                    $workout->only('id', 'name', 'description', 'date', 'level', 'time_cap', 'score', 'workout_type_id', 'exercises')
                ),
                'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::query()->select('id', 'name', 'description')->get()),
            ]
        );
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
            $workout->update($request->validated());

            foreach ($workout->exercises as $exercise) {
                ExerciseWorkout::find($exercise->pivot->id)->delete();
            }

            if (is_array($request->input('exercises'))) {
                foreach ($request->input('exercises') as $exerciseKey => $exercise) {
                    $exerciseWorkout = ExerciseWorkout::create([
                        'exercise_id' => $exercise['id'],
                        'workout_id' => $workout->id,
                        'position' => $exerciseKey,
                        'note' => $exercise['note'],
                    ]);

                    if (!is_array($exercise['sets'])) {
                        continue;
                    }

                    foreach ($exercise['sets'] as $setKey => $set) {
                        $newSet['repetitions'] = isset($set['repetitions']) ? intval($set['repetitions']) : 0;
                        $newSet['weight'] = isset($set['weight']) ? intval($set['weight']) : 0;
                        $newSet['distance'] = isset($set['distance']) ? floatval($set['distance']) : 0;
                        $newSet['calories'] = isset($set['calories']) ? intval($set['calories']) : 0;
                        $newSet['minutes'] = isset($set['minutes']) ? intval($set['minutes']) : 0;

                        // discard empty sets
                        if (!array_sum($newSet)) {
                            continue;
                        }

                        $newSet['exercise_workout_id'] = $exerciseWorkout->id;
                        $newSet['position'] = $setKey;
                        ExerciseWorkoutSet::create($newSet);
                    }
                }
            }

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
