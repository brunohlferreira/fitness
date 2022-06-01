<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseRequest;
use App\Http\Resources\BodyPartResource;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\WorkoutResource;
use App\Models\BodyPart;
use App\Models\Equipment;
use App\Models\Exercise;
use App\Models\Workout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Request;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Request::has('modalSearch')) {
            if (!Request::input('modalSearch')) {
                return [];
            }

            return Exercise::query()->where('name', 'like', '%' . Request::input('modalSearch') . '%')->select('id', 'name')->get();
        }

        return Inertia::render('Exercises/Index', [
            'exercises' => ExerciseResource::collection(
                Exercise::query()
                    ->select('id', 'name')
                    ->when(Request::input('search'), function ($query) {
                        $query->where('name', 'like', '%' . Request::input('search') . '%');
                    })
                    ->paginate(15)
                    ->withQueryString()
            ),
            'filters' => Request::only(['search']),
            'can' => [
                'create' => Gate::allows('Exercise'),
                'update' => Gate::allows('Exercise'),
                'delete' => Gate::allows('Exercise'),
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
        $this->authorize('Exercise');

        return Inertia::render('Exercises/Create', [
            'bodyParts' => BodyPartResource::collection(BodyPart::query()->select('id', 'name')->get()),
            'equipments' => EquipmentResource::collection(Equipment::query()->select('id', 'name')->selectRaw('0 AS selected')->get()),
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
        $this->authorize('Exercise');

        DB::beginTransaction();
        try {
            $exercise = Exercise::create($request->validated());

            $bodyParts = [];
            if (is_array($request->input('bodyParts'))) {
                foreach ($request->input('bodyParts') as $bodyPartKey => $bodyPart) {
                    $bodyParts[$bodyPart['id']] = [
                        'impact' => $bodyPart['impact'],
                    ];
                }
            }
            if (count($bodyParts)) {
                $exercise->bodyParts()->sync($bodyParts);
            }

            $equipments = [];
            if (is_array($request->input('equipments'))) {
                foreach ($request->input('equipments') as $equipmentKey => $equipment) {
                    $equipments[] = $equipment['id'];
                }
            }
            if (count($equipments)) {
                $exercise->equipments()->sync($equipments);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
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
                'attempts' => WorkoutResource::collection(
                    Workout::query()
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
                        ->get()
                ),
                'can' => [
                    'update' => Gate::allows('Exercise'),
                ],
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
        $this->authorize('Exercise');

        return Inertia::render('Exercises/Edit', [
            'exercise' => new ExerciseResource($exercise->only('id', 'name', 'description', 'bilateral')),
            'bodyParts' => BodyPartResource::collection(
                BodyPart::query()
                    ->select('body_parts.id', 'body_parts.name', 'body_part_exercise.impact')
                    ->leftJoin('body_part_exercise', function ($join) use ($exercise) {
                        $join->on('body_parts.id', 'body_part_exercise.body_part_id')->where('body_part_exercise.exercise_id', $exercise->id);
                    })
                    ->get()
            ),
            'equipments' => EquipmentResource::collection(
                Equipment::query()
                    ->select('equipments.id', 'equipments.name')
                    ->selectRaw('equipment_exercise.id IS NOT NULL AS selected')
                    ->leftJoin('equipment_exercise', function ($join) use ($exercise) {
                        $join->on('equipments.id', 'equipment_exercise.equipment_id')->where('equipment_exercise.exercise_id', $exercise->id);
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
        $this->authorize('Exercise');

        DB::beginTransaction();
        try {
            $exercise->update($request->validated());

            $bodyParts = [];
            if (is_array($request->input('bodyParts'))) {
                foreach ($request->input('bodyParts') as $bodyPartKey => $bodyPart) {
                    $bodyParts[$bodyPart['id']] = [
                        'impact' => $bodyPart['impact'],
                    ];
                }
            }
            if (count($bodyParts)) {
                $exercise->bodyParts()->sync($bodyParts);
            }

            $equipments = [];
            if (is_array($request->input('equipments'))) {
                foreach ($request->input('equipments') as $equipmentKey => $equipment) {
                    $equipments[] = $equipment['id'];
                }
            }
            if (count($equipments)) {
                $exercise->equipments()->sync($equipments);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
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
        $this->authorize('Exercise');

        DB::beginTransaction();
        try {
            $exercise->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            abort(500, 'Something went wrong. Please try again later.');
        }

        return response()->noContent();
    }
}
