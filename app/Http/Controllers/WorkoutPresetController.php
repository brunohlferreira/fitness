<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutPresetRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutPresetResource;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;
use App\Models\Workout;
use App\Models\WorkoutPreset;
use App\Models\WorkoutType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        return Inertia::render('WorkoutPresets/Index', [
            'workoutPresets' => WorkoutPresetResource::collection(
                WorkoutPreset::query()->select('id', 'name')->paginate(15)
            ),
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
            abort(403, 'You do not have access to this page or resource.');
        }

        return Inertia::render('WorkoutPresets/Create', [
            'workoutTypes' => WorkoutTypeResource::collection(
                WorkoutType::query()->select('id', 'name', 'description')->get()
            ),
        ]);
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
            abort(403, 'You do not have access to this page or resource.');
        }

        DB::beginTransaction();
        try {
            $workoutPreset = WorkoutPreset::create($request->validated());

            if (is_array($request->input('exercises'))) {
                foreach ($request->input('exercises') as $exerciseKey => $exercise) {
                    $exerciseWorkoutPreset = ExerciseWorkoutPreset::create([
                        'exercise_id' => $exercise['id'],
                        'workout_preset_id' => $workoutPreset->id,
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

                        $newSet['exercise_workout_preset_id'] = $exerciseWorkoutPreset->id;
                        $newSet['position'] = $setKey;
                        ExerciseWorkoutPresetSet::create($newSet);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workout-presets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutPreset $workoutPreset)
    {
        $workoutPreset->workout_type_name = $workoutPreset->type->name;
        $workoutPreset->workout_type_description = $workoutPreset->type->description;
        $workoutPreset->exercises = ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()]
            );
        });

        return Inertia::render(
            'WorkoutPresets/Show',
            [
                'workoutPreset' => new WorkoutPresetResource(
                    $workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_name', 'workout_type_description', 'exercises')
                ),
                'attempts' => WorkoutResource::collection(
                    Workout::query()
                        ->select('id', 'date', 'score')
                        ->where([
                            ['workout_preset_id', $workoutPreset->id],
                            ['created_by', Auth::user()->id],
                        ])
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
            abort(403, 'You do not have access to this page or resource.');
        }

        $workoutPreset->exercises = ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return Inertia::render(
            'WorkoutPresets/Edit',
            [
                'workoutPreset' => new WorkoutPresetResource(
                    $workoutPreset->only('id', 'name', 'description', 'level', 'time_cap', 'workout_type_id', 'exercises')
                ),
                'workoutTypes' => WorkoutTypeResource::collection(
                    WorkoutType::query()->select('id', 'name', 'description')->get()
                ),
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
            abort(403, 'You do not have access to this page or resource.');
        }

        DB::beginTransaction();
        try {
            $workoutPreset->update($request->validated());

            foreach ($workoutPreset->exercises as $exercise) {
                ExerciseWorkoutPreset::find($exercise->pivot->id)->delete();
            }

            if (is_array($request->input('exercises'))) {
                foreach ($request->input('exercises') as $exerciseKey => $exercise) {
                    $exerciseWorkoutPreset = ExerciseWorkoutPreset::create([
                        'exercise_id' => $exercise['id'],
                        'workout_preset_id' => $workoutPreset->id,
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

                        $newSet['exercise_workout_preset_id'] = $exerciseWorkoutPreset->id;
                        $newSet['position'] = $setKey;
                        ExerciseWorkoutPresetSet::create($newSet);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workout-presets.index');
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
            abort(403, 'You do not have access to this page or resource.');
        }

        DB::beginTransaction();
        try {
            $workoutPreset->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            abort(500, 'Something went wrong. Please try again later.');
        }

        return response()->noContent();
    }
}
