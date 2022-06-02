<?php

namespace App\Services;

use App\Models\Exercise;

class ExerciseService
{
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
