<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkoutPresetRequest;
use App\Http\Resources\WorkoutPresetResource;
use App\Http\Resources\WorkoutResource;
use App\Http\Resources\WorkoutTypeResource;
use App\Models\Workout;
use App\Models\WorkoutPreset;
use App\Services\WorkoutPresetService;
use App\Services\WorkoutTypeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class WorkoutPresetController extends Controller
{
    private $workoutPresetService;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct(WorkoutPresetService $workoutPresetService)
    {
        $this->workoutPresetService = $workoutPresetService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('WorkoutPresets/Index', [
            'workoutPresets' => WorkoutPresetResource::collection(
                WorkoutPreset::query()->select('id', 'name')->paginate(15)
            ),
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
    public function create(WorkoutTypeService $workoutType)
    {
        $this->authorize('WorkoutPreset');

        return Inertia::render('WorkoutPresets/Create', [
            'workoutTypes' => WorkoutTypeResource::collection(
                $workoutType->getAll()
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutPresetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WorkoutPresetRequest $request)
    {
        $this->authorize('WorkoutPreset');

        DB::beginTransaction();
        try {
            $this->workoutPresetService->store($request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workout-presets.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function show(WorkoutPreset $workoutPreset)
    {
        return Inertia::render('WorkoutPresets/Show', [
            'workoutPreset' => new WorkoutPresetResource(
                $this->workoutPresetService->show($workoutPreset)
            ),
            'attempts' => WorkoutResource::collection(
                Workout::query()
                    ->select('id', 'date', 'score')
                    ->where([
                        ['workout_preset_id', $workoutPreset->id],
                        ['created_by', Auth::user()->id],
                    ])
                    ->orderBy('date', 'desc')
                    ->limit(5)
                    ->get()
            ),
            'can' => [
                'update' => Gate::allows('WorkoutPreset'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkoutPreset $workoutPreset, WorkoutTypeService $workoutType)
    {
        $this->authorize('WorkoutPreset');

        return Inertia::render('WorkoutPresets/Edit', [
            'workoutPreset' => new WorkoutPresetResource(
                $this->workoutPresetService->edit($workoutPreset)
            ),
            'workoutTypes' => WorkoutTypeResource::collection(
                $workoutType->getAll()
            ),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\WorkoutPresetRequest  $request
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function update(WorkoutPresetRequest $request, WorkoutPreset $workoutPreset)
    {
        $this->authorize('WorkoutPreset');

        DB::beginTransaction();
        try {
            $this->workoutPresetService->update($workoutPreset, $request->validated());

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return back()->withErrors('Something went wrong. Please try again.');
        }

        return redirect()->route('workout-presets.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkoutPreset  $workoutPreset
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkoutPreset $workoutPreset)
    {
        $this->authorize('WorkoutPreset');

        DB::beginTransaction();
        try {
            $workoutPreset->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            abort(500, 'Something went wrong. Please try again later.');
        }

        return response()->noContent();
    }
}
