<?php

namespace App\Services;

use App\Http\Resources\ExerciseResource;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutPresetSet;
use App\Models\WorkoutPreset;
use Illuminate\Support\Arr;

class WorkoutPresetService
{
    public function store(array $dataValidated): void
    {
        $workoutPreset = WorkoutPreset::create($dataValidated);

        if (!isset($dataValidated['exercises'])) {
            return;
        }

        foreach ($dataValidated['exercises'] as $exerciseKey => $exercise) {
            $exerciseWorkoutPreset = ExerciseWorkoutPreset::create([
                'exercise_id' => $exercise['id'],
                'workout_preset_id' => $workoutPreset->id,
                'position' => intval($exerciseKey),
                'note' => $exercise['note'],
            ]);

            if (!isset($exercise['sets'])) {
                continue;
            }

            foreach ($exercise['sets'] as $setKey => $set) {
                $setData = Arr::only($set, ['repetitions', 'weight', 'distance', 'calories', 'minutes']);

                // discard empty sets
                if (!array_sum($setData)) {
                    continue;
                }

                $setData['exercise_workout_preset_id'] = $exerciseWorkoutPreset->id;
                $setData['position'] = $setKey;
                ExerciseWorkoutPresetSet::create($setData);
            }
        }
    }

    public function show(WorkoutPreset $workoutPreset): array
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

        return $workoutPreset->only(
            'id',
            'name',
            'description',
            'level',
            'time_cap',
            'workout_type_name',
            'workout_type_description',
            'exercises',
        );
    }

    public function edit(WorkoutPreset $workoutPreset): array
    {
        $workoutPreset->exercises = ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return $workoutPreset->only(
            'id',
            'name',
            'description',
            'level',
            'time_cap',
            'workout_type_id',
            'exercises',
        );
    }

    public function update(WorkoutPreset $workoutPreset, array $dataValidated): void
    {
        $workoutPreset->update($dataValidated);

        foreach ($workoutPreset->exercises as $exercise) {
            ExerciseWorkoutPreset::find($exercise->pivot->id)->delete();
        }

        if (!isset($dataValidated['exercises'])) {
            return;
        }

        foreach ($dataValidated['exercises'] as $exerciseKey => $exercise) {
            $exerciseWorkoutPreset = ExerciseWorkoutPreset::create([
                'exercise_id' => $exercise['id'],
                'workout_preset_id' => $workoutPreset->id,
                'position' => intval($exerciseKey),
                'note' => $exercise['note'],
            ]);

            if (!isset($exercise['sets'])) {
                continue;
            }

            foreach ($exercise['sets'] as $setKey => $set) {
                $setData = Arr::only($set, ['repetitions', 'weight', 'distance', 'calories', 'minutes']);

                // discard empty sets
                if (!array_sum($setData)) {
                    continue;
                }

                $setData['exercise_workout_preset_id'] = $exerciseWorkoutPreset->id;
                $setData['position'] = $setKey;
                ExerciseWorkoutPresetSet::create($setData);
            }
        }
    }
}
