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
        return Inertia::render('Backoffice/BodyParts', ['bodyParts' => BodyPartResource::collection(BodyPart::all())]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BodyPartRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BodyPartRequest $request)
    {
        $bodyPart = BodyPart::create($request->validated());

        return new BodyPartResource($bodyPart);
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
        //
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

        return new BodyPartResource($bodyPart);
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
