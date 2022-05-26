<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('User')) {
            abort(403, 'You do not have access to this page or resource.');
        }

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection(
                User::query()
                    ->select('users.id', 'roles.name AS role')
                    ->selectRaw('CONCAT(users.name, " (", users.email, ")") AS name')
                    ->leftJoin('model_has_roles', function ($join) {
                        $join->on('model_has_roles.model_id', 'users.id')->where('model_has_roles.model_type', "App\\Models\\User");
                    })
                    ->leftJoin('roles', function ($join) {
                        $join->on('model_has_roles.role_id', 'roles.id');
                    })
                    ->where('users.id', '>', 1)
                    ->orderByRaw('roles.id IS NULL')
                    ->orderBy('roles.id', 'ASC')
                    ->orderBy('users.name', 'ASC')
                    ->paginate(15)
            ),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('User')) {
            abort(403, 'You do not have access to this page or resource.');
        }

        return Inertia::render('Users/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if (!Gate::allows('User')) {
            abort(403, 'You do not have access to this page or resource.');
        }

        $request->validated();

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->route('users.index');
    }
}
