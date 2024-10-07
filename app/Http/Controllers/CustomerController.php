<?php

namespace App\Http\Controllers;

use App\Actions\Customers\CustomerTable;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request, CustomerTable $action): Response
    {
        return Inertia::render('Customers/Index', [
            'table' => fn () => CustomerResource::collection($action->handle($request->limit ?? 10)),
        ]);
    }
}
