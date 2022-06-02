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
use App\Services\ExerciseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ExerciseController extends Controller
{
    private $exerciseService;

    public function __construct(ExerciseService $exerciseService)
    {
        $this->exerciseService = $exerciseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Exercises/Index', [
            'exercises' => ExerciseResource::collection(
                Exercise::query()
                    ->select('id', 'name')
                    ->when($request->input('search'), function ($query) {
                        $query->where('name', 'like', "%{$request->input('search')}%");
                    })
                    ->paginate(15)
                    ->withQueryString()
            ),
            'filters' => $request->only(['search']),
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
            'bodyParts' => BodyPartResource::collection(
                BodyPart::query()->select('id', 'name')->get()
            ),
            'equipments' => EquipmentResource::collection(
                Equipment::query()->select('id', 'name')->selectRaw('0 AS selected')->get()
            ),
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
            $this->exerciseService->store($request->validated());

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
        return Inertia::render('Exercises/Show', [
            'exercise' => new ExerciseResource(
                $this->exerciseService->show($exercise)
            ),
            'attempts' => WorkoutResource::collection(
                $this->exerciseService->getLastAttempts($exercise)
            ),
            'can' => [
                'update' => Gate::allows('Exercise'),
            ],
        ]);
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
            'exercise' => new ExerciseResource(
                $exercise->only('id', 'name', 'description', 'bilateral')
            ),
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
            $this->exerciseService->update($exercise, $request->validated());

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
