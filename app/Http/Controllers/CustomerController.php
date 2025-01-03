<?php

namespace App\Http\Controllers;

use App\Actions\Customers\CustomerTable;
use App\Actions\Invoices\InvoiceTable;
use App\Actions\Payments\PaymentTable;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\PaymentResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request, CustomerTable $action): Response
    {
        return Inertia::render('Customers/Index', [
            'table' => Inertia::defer(fn (): AnonymousResourceCollection => CustomerResource::collection($action->handle(['limit' => $request->limit]))),
        ]);
    }

    public function show(Customer $customer): Response
    {
        $customer->load([
            'order' => function (Builder $query): void {
                $query->active();
            },
        ]);

        return Inertia::render('Customers/Show', [
            'customer' => fn (): CustomerResource => new CustomerResource($customer),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        Gate::denyIf(fn (User $user): bool => ! $user->isAdmin());

        $data = $request->validated();
        $customer->update($data);

        return back()->with([
            'message' => 'Customer updated successfully',
        ]);
    }

    public function invoices(Request $request, Customer $customer, InvoiceTable $action): Response
    {
        return Inertia::render('Customers/Invoice', [
            'customer' => new CustomerResource($customer),
            'table' => Inertia::defer(function () use ($action, $request, $customer): AnonymousResourceCollection {

                return InvoiceResource::collection($action->handle([
                    'limit' => $request->limit,
                    'customer_id' => $customer->id,
                ]));

            }),
        ]);
    }

    public function payments(Request $request, Customer $customer, PaymentTable $action): Response
    {
        return Inertia::render('Customers/Payment', [
            'customer' => new CustomerResource($customer),
            'table' => Inertia::defer(function () use ($action, $request, $customer): AnonymousResourceCollection {

                return PaymentResource::collection($action->handle([
                    'limit' => $request->limit,
                    'customer_id' => $customer->id,
                ]));

            }),
        ]);
    }
}
