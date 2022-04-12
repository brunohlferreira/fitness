<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Role')) {
            abort(403);
        }

        return Inertia::render('Roles/Index', ['roles' => RoleResource::collection(Role::where('id', '>', 1)->select('id', 'name')->paginate(15))]);
    }
}
