<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutPresetRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutPresetResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;
use App\Models\WorkoutPreset;
use App\Models\WorkoutType;
use Illuminate\Support\Arr;
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

        return Inertia::render($component, ['workoutPresets' => WorkoutPresetResource::collection(WorkoutPreset::select('id', 'name')->paginate(15))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Backoffice/WorkoutPresets/Create', ['workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name')->get())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutPresetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutPresetRequest $request)
    {
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

        return redirect()->route('backoffice.workoutPresets.index');
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
                'workoutPreset' => new WorkoutTypeResource($workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_name', 'workout_type_description', 'exercises')),
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
        return Inertia::render(
            'Backoffice/WorkoutPresets/Edit',
            [
                'workoutPreset' => new WorkoutPresetResource($workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_id')),
                'workoutExercises' => ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
                    return array_merge($exercise->only('id', 'name'), ['note' => $exercise->pivot->note], ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()]);
                }),
                'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name')->get()),
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
        $workoutPreset->update($request->validated());

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
                    if (isset($set['id'])) {
                        ExerciseWorkoutPresetSet::destroy($set['id']);
                    }

                    continue;
                }
                if (!isset($set['id'])) {
                    $set['id'] = 0;
                }

                $set['exercise_workout_preset_id'] = $exercise->pivot->id;
                $set['position'] = $setKey;
                ExerciseWorkoutPresetSet::updateOrCreate(
                    ['id' => $set['id']],
                    $set
                );
            }
        }

        return redirect()->route('backoffice.workoutPresets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutPreset $workoutPreset)
    {
        foreach ($workoutPreset->exercises as $exercise) {
            ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->map->delete();
        };
        $workoutPreset->exercises()->detach();
        $workoutPreset->delete();

        return response()->noContent();
    }

    /**
     * Show the form for adding a new exercise.
     *
     * @return \Illuminate\Http\Response
     */
    public function addExercise(WorkoutPreset $workoutPreset)
    {
        return Inertia::render('Backoffice/WorkoutPresets/Exercises/Add', ['workoutPreset' => new WorkoutPresetResource($workoutPreset->only('id'))]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function editExercise(WorkoutPreset $workoutPreset)
    {
        return Inertia::render(
            'Backoffice/WorkoutPresets/Edit',
            [
                'workoutPreset' => new WorkoutPresetResource($workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_id')),
                'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name')->get()),
            ]
        );
    }
}
