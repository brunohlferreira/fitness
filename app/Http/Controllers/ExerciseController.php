<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseRequest;
use App\Http\Resources\BodyPartResource;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\ExerciseResource;
use App\Models\BodyPart;
use App\Models\Equipment;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('modalSearch')) {
            return Exercise::where('name', 'like', '%' . $request->input('modalSearch') . '%')->select('id', 'name')->get();
        }
        return Inertia::render('Exercises/Index', [
            'exercises' => ExerciseResource::collection(Exercise::select('id', 'name')->paginate(15)),
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
        if (!Gate::allows('Exercise')) {
            abort(403);
        }

        return Inertia::render('Exercises/Create', [
            'bodyParts' => BodyPartResource::collection(BodyPart::select('id', 'name')->get()),
            'equipments' => EquipmentResource::collection(Equipment::select('id', 'name')->selectRaw('0 AS selected')->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ExerciseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExerciseRequest $request)
    {
        if (!Gate::allows('Exercise')) {
            abort(403);
        }

        $exercise = Exercise::create($request->validated());

        $bodyParts = [];
        foreach ($request->input('bodyParts') as $bodyPartKey => $bodyPart) {
            $bodyParts[$bodyPart['id']] = [
                'impact' => $bodyPart['impact'],
            ];
        }
        if (count($bodyParts)) {
            $exercise->bodyParts()->sync($bodyParts);
        }

        $equipments = [];
        foreach ($request->input('equipments') as $equipmentKey => $equipment) {
            $equipments[] = $equipment['id'];
        }
        if (count($equipments)) {
            $exercise->equipments()->sync($equipments);
        }

        return redirect()->route('exercises.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        /*$workoutPreset->exercises = ExerciseResource::collection($workoutPreset->exercises)->map(function ($exercise) {
            return array_merge($exercise->only('id', 'name'), ['note' => $exercise->pivot->note], ['sets' => ExerciseWorkoutPreset::find($exercise->pivot->id)->sets->toArray()]);
        });
        $workoutPreset->workout_type_name = $workoutPreset->type->name;
        $workoutPreset->workout_type_description = $workoutPreset->type->description;*/
        $exercise->bodyParts = EquipmentResource::collection($exercise->bodyParts)->map(function ($bodyPart) {
            return array_merge($bodyPart->only('id', 'name'), ['impact' => $bodyPart->pivot->impact]);
        });
        $exercise->equipments = EquipmentResource::collection($exercise->equipments)->map(function ($equipment) {
            return $equipment->only('id', 'name');
        });

        return Inertia::render(
            'Exercises/Show',
            [
                'exercise' => new ExerciseResource($exercise->only('id', 'name', 'description', 'bilateral', 'bodyParts', 'equipments')),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercise $exercise)
    {
        if (!Gate::allows('Exercise')) {
            abort(403);
        }

        return Inertia::render('Exercises/Edit', [
            'exercise' => new ExerciseResource($exercise->only('id', 'name', 'description', 'bilateral')),
            'bodyParts' => BodyPartResource::collection(
                BodyPart::select('body_parts.id', 'body_parts.name', 'body_part_exercise.impact')
                    ->leftJoin('body_part_exercise', function ($join) use ($exercise) {
                        $join->on('body_parts.id', '=', 'body_part_exercise.body_part_id')->where('body_part_exercise.exercise_id', '=', $exercise->id);
                    })
                    ->get()
            ),
            'equipments' => EquipmentResource::collection(
                Equipment::select('equipments.id', 'equipments.name')
                    ->selectRaw('equipment_exercise.id IS NOT NULL AS selected')
                    ->leftJoin('equipment_exercise', function ($join) use ($exercise) {
                        $join->on('equipments.id', '=', 'equipment_exercise.equipment_id')->where('equipment_exercise.exercise_id', '=', $exercise->id);
                    })
                    ->get()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ExerciseRequest  $request
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(ExerciseRequest $request, Exercise $exercise)
    {
        if (!Gate::allows('Exercise')) {
            abort(403);
        }

        $exercise->update($request->validated());

        $bodyParts = [];
        foreach ($request->input('bodyParts') as $bodyPartKey => $bodyPart) {
            $bodyParts[$bodyPart['id']] = [
                'impact' => $bodyPart['impact'],
            ];
        }
        if (count($bodyParts)) {
            $exercise->bodyParts()->sync($bodyParts);
        }

        $equipments = [];
        foreach ($request->input('equipments') as $equipmentKey => $equipment) {
            $equipments[] = $equipment['id'];
        }
        if (count($equipments)) {
            $exercise->equipments()->sync($equipments);
        }

        return redirect()->route('exercises.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        if (!Gate::allows('Exercise')) {
            abort(403);
        }

        $exercise->bodyParts()->detach();
        $exercise->equipments()->detach();
        $exercise->delete();

        return response()->noContent();
    }
}
