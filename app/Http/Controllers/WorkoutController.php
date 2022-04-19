<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\ExerciseWorkout;
use App\Models\ExerciseWorkoutSet;
use App\Models\User;
use App\Models\Workout;
use App\Models\WorkoutType;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
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
            'workouts' => WorkoutResource::collection(Workout::where('created_by', Auth::user()->id)->select('id', 'name', 'date')->orderBy('date', 'desc')->paginate(15)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Workouts/Create', [
            'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name', 'description')->get()),
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
        $workout = Workout::create($request->validated());

        $exercises = [];
        foreach ($request->input('exercises') as $exerciseKey => $exercise) {
            $exercises[$exercise['id']] = [
                'position' => $exerciseKey,
                'note' => $exercise['note'],
            ];
            $exercisesSets[$exercise['id']] = $exercise['sets'];
        }
        if (count($exercises)) {
            $workout->exercises()->sync($exercises);
        }

        foreach ($workout->exercises as $exercise) {
            if (!count($exercisesSets[$exercise->id])) {
                continue;
            }

            foreach ($exercisesSets[$exercise->id] as $setKey => $set) {
                if (!count(Arr::where(Arr::except($set, 'id'), function ($value) {return $value > 0;}))) {
                    continue;
                }
                ExerciseWorkoutSet::create([
                    'exercise_workout_id' => $exercise->pivot->id,
                    'position' => $setKey,
                    'repetitions' => $set['repetitions'],
                    'weight' => $set['weight'],
                    'distance' => $set['distance'],
                    'calories' => $set['calories'],
                    'minutes' => $set['minutes'],
                ]);
            }
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
        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
            );
        });
        $workout->workout_type_name = $workout->type->name;
        $workout->workout_type_description = $workout->type->description;

        return Inertia::render(
            'Workouts/Show',
            [
                'workout' => new WorkoutTypeResource($workout->only('id', 'name', 'description', 'date', 'level', 'time_cap', 'workout_type_name', 'workout_type_description', 'exercises')),
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
        return Inertia::render(
            'Workouts/Edit',
            [
                'workout' => new WorkoutResource($workout->only('id', 'name', 'description', 'date', 'level', 'time_cap', 'workout_type_id')),
                'workoutExercises' => ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
                    return array_merge($exercise->only('id', 'name'), ['note' => $exercise->pivot->note], ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()]);
                }),
                'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name', 'description')->get()),
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
        $workout->update($request->validated());

        $exercises = [];
        foreach ($request->input('exercises') as $exerciseKey => $exercise) {
            $exercises[$exercise['id']] = [
                'position' => $exerciseKey,
                'note' => $exercise['note'],
            ];
            $exercisesSets[$exercise['id']] = $exercise['sets'];
        }

        foreach ($workout->exercises as $exercise) {
            if (!isset($exercisesSets[$exercise->id]) || !count($exercisesSets[$exercise->id])) {
                ExerciseWorkout::find($exercise->pivot->id)->sets->map->delete();
                continue;
            }

            foreach ($exercisesSets[$exercise->id] as $setKey => $set) {
                if (!count(Arr::where(Arr::except($set, 'id'), function ($value) {return $value > 0;}))) {
                    if (isset($set['id'])) {
                        ExerciseWorkoutSet::destroy($set['id']);
                    }

                    continue;
                }

                if (!isset($set['id'])) {
                    $set['id'] = null;
                }

                $set['exercise_workout_id'] = $exercise->pivot->id;
                $set['position'] = $setKey;
                ExerciseWorkoutSet::updateOrCreate(
                    ['id' => $set['id']],
                    $set
                );
            }
        }

        if (count($exercises)) {
            $workout->exercises()->sync($exercises);
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
        foreach ($workout->exercises as $exercise) {
            ExerciseWorkout::find($exercise->pivot->id)->sets->map->delete();
        };
        $workout->exercises()->detach();
        $workout->delete();

        return response()->noContent();
    }

    /**
     * Copy the specified resource to a new Workout and assign it to the user.
     *
     * @param  \App\Models\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function repeat(Workout $workout)
    {
        $this->authorize('view', $workout);

        $newWorkout = Workout::create([
            'name' => $workout->name,
            'description' => $workout->description,
            'date' => date('Y-m-d'),
            'level' => $workout->level,
            'time_cap' => $workout->time_cap,
            'workout_type_id' => $workout->workout_type_id,
            'workout_preset_id' => $workout->workout_preset_id,
            'created_by' => Auth::user()->id,
        ]);

        foreach ($workout->exercises as $exerciseKey => $exercise) {
            $exerciseWorkout = ExerciseWorkout::create([
                'exercise_id' => $exercise->id,
                'workout_id' => $newWorkout->id,
                'position' => $exerciseKey,
                'note' => $exercise->note,
            ]);

            foreach (ExerciseWorkout::find($exercise->pivot->id)->sets as $setKey => $set) {
                ExerciseWorkoutSet::create([
                    'exercise_workout_id' => $exerciseWorkout->id,
                    'position' => $setKey,
                    'repetitions' => $set->repetitions,
                    'weight' => $set->weight,
                    'distance' => $set->distance,
                    'calories' => $set->calories,
                    'minutes' => $set->minutes,
                ]);
            }
        }

        return response($newWorkout->id);
    }
}
