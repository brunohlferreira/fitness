<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('User');

        if ($user->id == 1) {
            abort(404, 'The resource requested could not be found on this server.');
        }

        $user->roleId = $user->roles->first()->id ?? null;

        return Inertia::render('Users/EditRole', [
            'user' => new UserResource($user->only('id', 'roleId')),
            'roles' => RoleResource::collection(Role::query()->select('id', 'name')->where('id', '>', 1)->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRoleRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRoleRequest $request, User $user)
    {
        $this->authorize('User');

        if ($user->id == 1) {
            abort(404, 'The resource requested could not be found on this server.');
        }

        $user->syncRoles([$request->validated()]);

        return redirect()->route('users.index');
    }
}
