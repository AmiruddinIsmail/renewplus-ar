<?php

namespace App\Http\Controllers;

use App\Actions\Users\UserTable;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request, UserTable $action): Response
    {
        return Inertia::render('Users/Index', [
            'table' => fn () => UserResource::collection($action->handle($request->limit ?? 10)),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
    }
}
