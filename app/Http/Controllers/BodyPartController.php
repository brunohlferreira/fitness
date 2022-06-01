<?php

namespace App\Http\Controllers;

use App\Http\Requests\BodyPartRequest;
use App\Http\Resources\BodyPartResource;
use App\Models\BodyPart;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class BodyPartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('BodyPart');

        return Inertia::render('BodyParts/Index', [
            'bodyParts' => BodyPartResource::collection(BodyPart::query()->select('id', 'name')->paginate(15)),
            'can' => [
                'create' => Gate::allows('BodyPart'),
                'update' => Gate::allows('BodyPart'),
                'delete' => Gate::allows('BodyPart'),
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
        $this->authorize('BodyPart');

        return Inertia::render('BodyParts/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BodyPartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BodyPartRequest $request)
    {
        $this->authorize('BodyPart');

        BodyPart::create($request->validated());

        return redirect()->route('body-parts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function show(BodyPart $bodyPart)
    {
        $this->authorize('BodyPart');

        return new BodyPartResource($bodyPart);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function edit(BodyPart $bodyPart)
    {
        $this->authorize('BodyPart');

        return Inertia::render('BodyParts/Edit', [
            'bodyPart' => new BodyPartResource($bodyPart->only('id', 'name')),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BodyPartRequest  $request
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function update(BodyPartRequest $request, BodyPart $bodyPart)
    {
        $this->authorize('BodyPart');

        $bodyPart->update($request->validated());

        return redirect()->route('body-parts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function destroy(BodyPart $bodyPart)
    {
        $this->authorize('BodyPart');

        $bodyPart->delete();

        return response()->noContent();
    }
}
