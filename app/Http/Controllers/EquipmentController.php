<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('Equipment');

        return Inertia::render('Equipments/Index', [
            'equipments' => EquipmentResource::collection(Equipment::query()->select('id', 'name')->paginate(15)),
            'can' => [
                'create' => Gate::allows('Equipment'),
                'update' => Gate::allows('Equipment'),
                'delete' => Gate::allows('Equipment'),
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
        $this->authorize('Equipment');

        return Inertia::render('Equipments/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EquipmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EquipmentRequest $request)
    {
        $this->authorize('Equipment');

        Equipment::create($request->validated());

        return redirect()->route('equipments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function show(Equipment $equipment)
    {
        $this->authorize('Equipment');

        return new EquipmentResource($equipment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        $this->authorize('Equipment');

        return Inertia::render('Equipments/Edit', [
            'equipment' => new EquipmentResource($equipment->only('id', 'name')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EquipmentRequest  $request
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $this->authorize('Equipment');

        $equipment->update($request->validated());

        return redirect()->route('equipments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Equipment $equipment)
    {
        $this->authorize('Equipment');

        $equipment->delete();

        return response()->noContent();
    }
}
