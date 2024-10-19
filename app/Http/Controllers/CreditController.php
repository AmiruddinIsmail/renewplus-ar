<?php

namespace App\Http\Controllers;

use App\Actions\Credits\CreditTable;
use App\Http\Resources\CreditResource;
use App\Models\Credit;
use App\Models\Customer;
use App\Utils\Helper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CreditController extends Controller
{
    public function index(CreditTable $action)
    {
        return Inertia::render('Credits/Index', [
            'table' => fn () => CreditResource::collection($action->handle($request->limit ?? 10)),
        ]);
    }

    public function create()
    {
        return Inertia::render('Credits/Create', [
            'customers' => fn () => Customer::select('id', 'name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customerId' => 'required',
            'issueAt' => 'required',
            'amount' => 'required | numeric',
        ]);

        Credit::create([
            'customer_id' => $data['customerId'],
            'reference_no' => Helper::referenceNoConvention('CRE', mt_rand(1, 9999), now()),
            'amount' => $data['amount'] * 100,
            'unresolved' => true,
            'unresolved_amount' => $data['amount'] * 100,
        ]);

        return to_route('credits.index')->with('success', 'Credit created successfully');
    }
}
