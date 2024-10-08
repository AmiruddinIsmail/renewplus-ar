<?php

namespace App\Actions\Invoices;

use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;

class CreateInvoice
{
    public function handle(Carbon $invoiceDate, array $data, Customer $customer): ?Invoice
    {
        if ($customer->contract_at > $invoiceDate->format('Y-m-d')) {
            return null;
        }

        // invoices completed
        if ($customer->invoices()->count() >= $customer->tenure) {
            return null;
        }

        // check if duplicated invoice & can eager load
        if ($customer->invoices()->where('issue_at', $invoiceDate->format('Y-m-d'))->exists()) {
            return null;
        }

        return $customer->invoices()->create($data);
    }
}
