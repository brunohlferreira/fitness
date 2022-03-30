<?php

namespace App\Http\Controllers;

use App\Http\Requests\BodyPartRequest;
use App\Http\Resources\BodyPartResource;
use App\Models\BodyPart;
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
        return Inertia::render('Backoffice/BodyParts/Index', ['bodyParts' => BodyPartResource::collection(BodyPart::paginate(15))]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Backoffice/BodyParts/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BodyPartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BodyPartRequest $request)
    {
        BodyPart::create($request->validated());

        return redirect()->route('backoffice.bodyParts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function show(BodyPart $bodyPart)
    {
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
        return Inertia::render('Backoffice/BodyParts/Edit', ['bodyPart' => new BodyPartResource($bodyPart)]);
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
        $bodyPart->update($request->validated());

        return redirect()->route('backoffice.bodyParts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BodyPart  $bodyPart
     * @return \Illuminate\Http\Response
     */
    public function destroy(BodyPart $bodyPart)
    {
        $bodyPart->delete();

        return response()->noContent();
    }
}
