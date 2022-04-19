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
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

        return Inertia::render('WorkoutTypes/Index', [
            'workoutTypes' => WorkoutTypeResource::collection(WorkoutType::select('id', 'name', 'description')->paginate(15)),
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
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

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
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

        WorkoutType::create($request->validated());

        return redirect()->route('workoutTypes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutType $workoutType)
    {
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

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
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

        return Inertia::render('WorkoutTypes/Edit', ['workoutType' => new WorkoutTypeResource($workoutType->only('id', 'name', 'description'))]);
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
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

        $workoutType->update($request->validated());

        return redirect()->route('workoutTypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutType  $workoutType
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutType $workoutType)
    {
        if (!Gate::allows('WorkoutType')) {
            abort(403);
        }

        $workoutType->delete();

        return response()->noContent();
    }
}
