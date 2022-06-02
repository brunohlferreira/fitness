<?php

namespace App\Services;

use App\Http\Resources\ExerciseResource;
use App\Models\ExerciseWorkout;
use App\Models\ExerciseWorkoutPreset;
use App\Models\ExerciseWorkoutSet;
use App\Models\Workout;
use App\Models\WorkoutPreset;
use Illuminate\Support\Arr;

class WorkoutService
{
    public function createFromWorkout(int $id): array
    {
        $workout = Workout::query()
            ->where([
                ['id', $id],
                ['created_by', Auth::user()->id],
            ])
            ->firstOrFail();

        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return $workout->only(
            'id',
            'name',
            'description',
            'level',
            'time_cap',
            'score',
            'workout_type_id',
            'workout_preset_id',
            'exercises',
        );
    }

    public function createFromWorkoutPreset(int $id): array
    {
        $workout = WorkoutPreset::query()
            ->where('id', $id)
            ->firstOrFail();

        $workout->score = '';
        $workout->workout_preset_id = $workout->id;
        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return $workout->only(
            'id',
            'name',
            'description',
            'level',
            'time_cap',
            'score',
            'workout_type_id',
            'workout_preset_id',
            'exercises',
        );
    }

    public function store(array $dataValidated): void
    {
        $workout = Workout::create($dataValidated);

        if (!isset($dataValidated['exercises'])) {
            return;
        }

        foreach ($dataValidated['exercises'] as $exerciseKey => $exercise) {
            $exerciseWorkout = ExerciseWorkout::create([
                'exercise_id' => $exercise['id'],
                'workout_id' => $workout->id,
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

                $setData['exercise_workout_id'] = $exerciseWorkout->id;
                $setData['position'] = $setKey;
                ExerciseWorkoutSet::create($setData);
            }
        }
    }

    public function show(Workout $workout): array
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

        return $workout->only(
            'id',
            'name',
            'description',
            'date',
            'level',
            'time_cap',
            'score',
            'workout_type_name',
            'workout_type_description',
            'exercises',
        );
    }

    public function edit(Workout $workout): array
    {
        $workout->exercises = ExerciseResource::collection($workout->exercises)->map(function ($exercise) {
            return array_merge(
                $exercise->only('id', 'name'),
                ['note' => $exercise->pivot->note],
                ['sets' => ExerciseWorkout::find($exercise->pivot->id)->sets->toArray()],
            );
        });

        return $workout->only(
            'id',
            'name',
            'description',
            'date',
            'level',
            'time_cap',
            'score',
            'workout_type_id',
            'exercises',
        );
    }

    public function update(Workout $workout, array $dataValidated): void
    {
        $workout->update($dataValidated);

        foreach ($workout->exercises as $exercise) {
            ExerciseWorkout::find($exercise->pivot->id)->delete();
        }

        if (!isset($dataValidated['exercises'])) {
            return;
        }

        foreach ($dataValidated['exercises'] as $exerciseKey => $exercise) {
            $exerciseWorkout = ExerciseWorkout::create([
                'exercise_id' => $exercise['id'],
                'workout_id' => $workout->id,
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

                $setData['exercise_workout_id'] = $exerciseWorkout->id;
                $setData['position'] = $setKey;
                ExerciseWorkoutSet::create($setData);
            }
        }
    }
}
