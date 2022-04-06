<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutTypeRequest;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\WorkoutType;
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
        return Inertia::render('Backoffice/WorkoutTypes/Index', ['workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name')->paginate(15))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Backoffice/WorkoutTypes/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutTypeRequest $request)
    {
        WorkoutType::create($request->validated());

        return redirect()->route('backoffice.workoutTypes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutType $workoutType)
    {
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
        return Inertia::render('Backoffice/WorkoutTypes/Edit', ['workoutType' => new WorkoutTypeResource($workoutType->only('id', 'name'))]);
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
        $workoutType->update($request->validated());

        return redirect()->route('backoffice.workoutTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutType $workoutType)
    {
        $workoutType->delete();

        return response()->noContent();
    }
}
