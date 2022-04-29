<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
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
            abort(403);
        }

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection(
                User::select('users.id', 'roles.name AS role')
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
            abort(403);
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
            abort(403);
        }

        $request->validated();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function editRole(User $user)
    {
        if ($user->id == 1) {
            abort(404);
        }

        if (!Gate::allows('User')) {
            abort(403);
        }

        $userRole = $user->roles->first();
        if (!$userRole) {
            $userRole = ['userId' => $user->id];
        } else {
            $userRole = array_merge(['userId' => $userRole->pivot->model_id], $userRole->only('id', 'name'));
        }

        return Inertia::render('Users/EditRole', [
            'role' => new RoleResource($userRole),
            'roles' => RoleResource::collection(Role::select('id', 'name')->where('id', '>', 1)->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, User $user)
    {
        if ($user->id == 1) {
            abort(404);
        }

        if (!Gate::allows('User')) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|integer',
        ]);

        $roles = $request->role ? [$request->role] : [];
        $user->syncRoles($roles);

        return redirect()->route('users.index');
    }
}
