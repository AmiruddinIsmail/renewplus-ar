<?php

namespace App\Http\Controllers;

use App\Actions\Invoices\InvoiceTable;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(Request $request, InvoiceTable $action): Response
    {
        return Inertia::render('Invoices/Index', [
            'table' => fn() => InvoiceResource::collection($action->handle($request->limit ?? 10)),
        ]);
    }

    public function show(Invoice $invoice): Response
    {
        $invoice->load('customer', 'payments', 'charges', 'credits');

        return Inertia::render('Invoices/Show', [
            'invoice' => fn() => InvoiceResource::make($invoice),
        ]);
    }
}
