<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\InvoiceTable;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(Request $request, InvoiceTable $action): Response
    {
        return Inertia::render('Invoices/Index', [
            'table' => Inertia::defer(function () use ($action, $request): AnonymousResourceCollection {

                return InvoiceResource::collection($action->handle([
                    'limit' => $request->limit,
                ]));

            }),
        ]);
    }

    public function show(Invoice $invoice): Response
    {
        $invoice->load('order', 'customer', 'charges');

        return Inertia::render('Invoices/Show', [
            'invoice' => fn (): InvoiceResource => InvoiceResource::make($invoice),
        ]);
    }
}
