<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $nav = [];

        if ($request->user()) {
            if ($request->user()->can('User')) {
                $nav[] = 
                    [
                        'name' => "Users",
                        'component' => "Users",
                        'route' => "users.index",
                    ];
            }
            if ($request->user()->can('BodyPart')) {
                $nav[] = 
                    [
                        'name' => "Body Parts",
                        'component' => "BodyParts",
                        'route' => "bodyParts.index",
                    ];
            }
            if ($request->user()->can('Equipment')) {
                $nav[] = 
                    [
                        'name' => "Equipments",
                        'component' => "Equipments",
                        'route' => "equipments.index",
                    ];
            }
            if ($request->user()->can('WorkoutType')) {
                $nav[] = 
                    [
                        'name' => "Workout Types",
                        'component' => "WorkoutTypes",
                        'route' => "workoutTypes.index",
                    ];
            }
        }

        return array_merge(parent::share($request), [
            'auth' => $request->user() ? [
                'user' => [
                    'name' => $request->user()->name,
                    'roles' => $request->user()->roles->map(function ($role) {
                        return $role->name;
                    }),
                ],
            ] : null,
            'nav' => $nav,
        ]);
    }
}
