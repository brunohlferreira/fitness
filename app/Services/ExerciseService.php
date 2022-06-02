<?php

namespace App\Services;

use App\Http\Resources\BodyPartResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Exercise;

class ExerciseService
{
    public function getLastAttempts(Exercise $exercise): Collection
    {
        return Workout::query()
            ->select('workouts.id', 'workouts.name', 'workouts.date')
            ->join('exercise_workout', function ($join) use ($exercise) {
                $join->on('workouts.id', 'exercise_workout.workout_id')
                    ->where('exercise_workout.exercise_id', $exercise->id);
            })
            ->where('workouts.created_by', Auth::user()->id)
            ->groupBy('workouts.id')
            ->groupBy('workouts.name')
            ->groupBy('workouts.date')
            ->groupByRaw('DATE(workouts.date)')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
    }

    public function store(array $dataValidated): void
    {
        $exercise = Exercise::create($dataValidated);

        if (isset($dataValidated['bodyParts'])) {
            $bodyParts = [];
            foreach ($dataValidated['bodyParts'] as $bodyPartKey => $bodyPart) {
                $bodyParts[$bodyPart['id']] = [
                    'impact' => $bodyPart['impact'],
                ];
            }
            $exercise->bodyParts()->sync($bodyParts);
        }

        if (isset($dataValidated['equipments'])) {
            $equipments = [];
            foreach ($dataValidated['equipments'] as $equipment) {
                $equipments[] = $equipment['id'];
            }
            $exercise->equipments()->sync($equipments);
        }
    }

    public function show(Exercise $exercise): array
    {
        $exercise->bodyParts = BodyPartResource::collection($exercise->bodyParts)->map(function ($bodyPart) {
            return array_merge($bodyPart->only('id', 'name'), ['impact' => $bodyPart->pivot->impact]);
        });

        $exercise->equipments = EquipmentResource::collection($exercise->equipments)->map(function ($equipment) {
            return $equipment->only('id', 'name');
        });

        return $exercise->only(
            'id',
            'name',
            'description',
            'bilateral',
            'bodyParts',
            'equipments',
        );
    }

    public function update(Exercise $exercise, array $dataValidated): void
    {
        $exercise->update($dataValidated);

        if (isset($dataValidated['bodyParts'])) {
            $bodyParts = [];
            foreach ($dataValidated['bodyParts'] as $bodyPartKey => $bodyPart) {
                $bodyParts[$bodyPart['id']] = [
                    'impact' => $bodyPart['impact'],
                ];
            }
            $exercise->bodyParts()->sync($bodyParts);
        }

        $equipments = [];
        if (isset($dataValidated['equipments'])) {
            foreach ($dataValidated['equipments'] as $equipment) {
                $equipments[] = $equipment['id'];
            }
            $exercise->equipments()->sync($equipments);
        }
    }
}
