<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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

        return Inertia::render('Users/Index', ['users' => UserResource::collection(User::where('id', '>', 1)->select('id', 'email AS name')->paginate(15))]);
    }
}
