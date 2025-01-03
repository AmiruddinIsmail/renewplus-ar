<?php

namespace App\Http\Controllers;

use App\Actions\Users\UserTable;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request, UserTable $action): Response
    {
        return Inertia::render('Users/Index', [
            'table' => fn (): AnonymousResourceCollection => UserResource::collection($action->handle([
                'limit' => $request->limit,
            ])),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request): void
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
    }
}
