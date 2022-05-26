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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

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
        if (!Gate::allows('BodyPart')) {
            abort(403, 'You do not have access to this page or resource.');
        }

        $bodyPart->delete();

        return response()->noContent();
    }
}
