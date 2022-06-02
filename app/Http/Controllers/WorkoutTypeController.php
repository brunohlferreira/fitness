<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutTypeRequest;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\WorkoutType;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class WorkoutTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('WorkoutType');

        return Inertia::render('WorkoutTypes/Index', [
            'workoutTypes' => WorkoutTypeResource::collection(
                WorkoutType::query()->select('id', 'name', 'description')->paginate(15)
            ),
            'can' => [
                'create' => Gate::allows('WorkoutType'),
                'update' => Gate::allows('WorkoutType'),
                'delete' => Gate::allows('WorkoutType'),
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
        $this->authorize('WorkoutType');

        return Inertia::render('WorkoutTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutTypeRequest $request)
    {
        $this->authorize('WorkoutType');

        WorkoutType::create($request->validated());

        return redirect()->route('workout-types.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutType $workoutType)
    {
        $this->authorize('WorkoutType');

        return new WorkoutTypeResource($workoutType);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkoutType $workoutType)
    {
        $this->authorize('WorkoutType');

        return Inertia::render('WorkoutTypes/Edit', [
            'workoutType' => new WorkoutTypeResource(
                $workoutType->only('id', 'name', 'description')
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutTypeRequest  $request
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function update(WorkoutTypeRequest $request, WorkoutType $workoutType)
    {
        $this->authorize('WorkoutType');

        $workoutType->update($request->validated());

        return redirect()->route('workout-types.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutType $workoutType)
    {
        $this->authorize('WorkoutType');

        $workoutType->delete();

        return response()->noContent();
    }
}
