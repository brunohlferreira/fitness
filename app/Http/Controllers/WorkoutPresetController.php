<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutPresetRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutPresetResource;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\ExerciseWorkout;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;
use App\Models\ExerciseWorkoutSet;
use App\Models\Workout;
use App\Models\WorkoutPreset;
use App\Models\WorkoutType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Inertia\Inertia;

class WorkoutPresetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Str::contains(request()->path(), 'backoffice')) {
            $component = 'WorkoutPresets/Index';
        } else {
            $component = 'Backoffice/WorkoutPresets/Index';
        }

        return Inertia::render($component, [
            'workoutPresets' => WorkoutPresetResource::collection(WorkoutPreset::select('id', 'name')->paginate(15)),
            'can' => [
                'create' => Gate::allows('WorkoutPreset'),
                'update' => Gate::allows('WorkoutPreset'),
                'delete' => Gate::allows('WorkoutPreset'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('WorkoutPreset')) {
            abort(403);
        }

        return Inertia::render('WorkoutPresets/Create', ['workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name', 'description')->get())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutPresetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutPresetRequest $request)
    {
        if (!Gate::allows('WorkoutPreset')) {
            abort(403);
        }

        $workoutPreset = WorkoutPreset::create($request->validated());

        $exercises = [];
        foreach ($request->input('exercises') as $exerciseKey => $exercise) {
            $exercises[$exercise['id']] = [
                'position' => $exerciseKey,
                'note' => $exercise['note'],
            ];
            $exercisesSets[$exercise['id']] = $exercise['sets'];
        }
        if (count($exercises)) {
            $workoutPreset->exercises()->sync($exercises);
        }

        foreach ($workoutPreset->exercises as $exercise) {
            if (!count($exercisesSets[$exercise->id])) {
                continue;
            }

            foreach ($exercisesSets[$exercise->id] as $setKey => $set) {
                if (!count(Arr::where(Arr::except($set, 'id'), function ($value) {return $value > 0;}))) {
                    continue;
                }
                ExerciseWorkoutPresetSet::create([
                    'exercise_workout_preset_id' => $exercise->pivot->id,
                    'position' => $setKey,
                    'repetitions' => $set['repetitions'],
                    'weight' => $set['weight'],
                    'distance' => $set['distance'],
                    'calories' => $set['calories'],
                    'minutes' => $set['minutes'],
                ]);
            }
        }

        return redirect()->route('workoutPresets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutPreset $workoutPreset)
    {
        $workoutPreset->exercises = ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
            return array_merge($exercise->only('id', 'name'), ['note' => $exercise->pivot->note], ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()]);
        });
        $workoutPreset->workout_type_name = $workoutPreset->type->name;
        $workoutPreset->workout_type_description = $workoutPreset->type->description;

        return Inertia::render(
            'WorkoutPresets/Show',
            [
                'workoutPreset' => new WorkoutPresetResource($workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_name', 'workout_type_description', 'exercises')),
                'attempts' => WorkoutResource::collection(
                    Workout::select('id', 'date', 'score')
                        ->where('workout_preset_id', '=', $workoutPreset->id)
                        ->where('created_by', '=', Auth::user()->id)
                        ->orderBy('date', 'desc')
                        ->limit(5)
                        ->get()
                ),
                'can' => [
                    'update' => Gate::allows('WorkoutPreset'),
                ],
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkoutPreset $workoutPreset)
    {
        if (!Gate::allows('WorkoutPreset')) {
            abort(403);
        }

        return Inertia::render(
            'WorkoutPresets/Edit',
            [
                'workoutPreset' => new WorkoutPresetResource($workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_id')),
                'workoutExercises' => ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
                    return array_merge($exercise->only('id', 'name'), ['note' => $exercise->pivot->note], ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()]);
                }),
                'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name', 'description')->get()),
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutPresetRequest  $request
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function update(WorkoutPresetRequest $request, WorkoutPreset $workoutPreset)
    {
        if (!Gate::allows('WorkoutPreset')) {
            abort(403);
        }

        $workoutPreset->update($request->validated());

        $exercises = [];
        foreach ($request->input('exercises') as $exerciseKey => $exercise) {
            $exercises[$exercise['id']] = [
                'position' => $exerciseKey,
                'note' => $exercise['note'],
            ];
            $exercisesSets[$exercise['id']] = $exercise['sets'];
        }

        foreach ($workoutPreset->exercises as $exercise) {
            if (!isset($exercisesSets[$exercise->id]) || !count($exercisesSets[$exercise->id])) {
                ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->map->delete();
                continue;
            }

            foreach ($exercisesSets[$exercise->id] as $setKey => $set) {
                if (!count(Arr::where(Arr::except($set, 'id'), function ($value) {return $value > 0;}))) {
                    if (isset($set['id'])) {
                        ExerciseWorkoutPresetSet::destroy($set['id']);
                    }

                    continue;
                }

                if (!isset($set['id'])) {
                    $set['id'] = null;
                }

                $set['exercise_workout_preset_id'] = $exercise->pivot->id;
                $set['position'] = $setKey;
                ExerciseWorkoutPresetSet::updateOrCreate(
                    ['id' => $set['id']],
                    $set
                );
            }
        }

        if (count($exercises)) {
            $workoutPreset->exercises()->sync($exercises);
        }

        return redirect()->route('workoutPresets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutPreset $workoutPreset)
    {
        if (!Gate::allows('WorkoutPreset')) {
            abort(403);
        }

        foreach ($workoutPreset->exercises as $exercise) {
            ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->map->delete();
        };
        $workoutPreset->exercises()->detach();
        $workoutPreset->delete();

        return response()->noContent();
    }

    /**
     * Copy the specified resource to a new Workout and assign it to the user.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function repeat(WorkoutPreset $workoutPreset)
    {
        $workout = Workout::create([
            'name' => $workoutPreset->name,
            'description' => $workoutPreset->description,
            'date' => date('Y-m-d'),
            'level' => $workoutPreset->level,
            'time_cap' => $workoutPreset->time_cap,
            'workout_type_id' => $workoutPreset->workout_type_id,
            'workout_preset_id' => $workoutPreset->id,
            'created_by' => Auth::user()->id,
        ]);

        foreach ($workoutPreset->exercises as $exerciseKey => $exercise) {
            $exerciseWorkout = ExerciseWorkout::create([
                'exercise_id' => $exercise->id,
                'workout_id' => $workout->id,
                'position' => $exerciseKey,
                'note' => $exercise->note,
            ]);

            foreach (ExerciseWorkoutPreset::find($exercise->pivot->id)->sets as $setKey => $set) {
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

        return response($workout->id);
    }
}
