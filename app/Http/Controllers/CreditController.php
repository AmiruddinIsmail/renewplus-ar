<?php

namespace App\Http\Controllers;

use App\Actions\Credits\CreditTable;
use App\Http\Resources\CreditResource;
use Inertia\Inertia;

class CreditController extends Controller
{
    public function index(CreditTable $action)
    {
        return Inertia::render('Credits/Index', [
            'table' => fn () => CreditResource::collection($action->handle($request->limit ?? 10)),
        ]);
    }
}
