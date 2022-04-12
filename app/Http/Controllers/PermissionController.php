<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class PermissionController extends Controller
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

        return Inertia::render('Permissions/Index', ['permissions' => PermissionResource::collection(Permission::select('id', 'name')->paginate(15))]);
    }
}
