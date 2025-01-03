<?php

namespace App\Features\Automation\Validators;

use App\Models\Order;
use Carbon\Carbon;

class CreateInvoiceValidator
{
    public function handle(Carbon $issueAt, Order $order): bool
    {
        // return if issueAt greater than contract date
        if ($issueAt->lt($order->contract_at)) {
            return false;
        }

        // return if total invoices >= tenure
        if ($order->invoices()->count() >= $order->tenure) {
            return false;
        }

        // order already completed
        if ($order->completed_at !== null) {
            return false;
        }

        // return if invoice already created
        $exists = $order
            ->invoices()
            ->where('issue_at', $issueAt->format('Y-m-d'))
            ->exists();

        if ($exists) {

            return false;
        }

        return true;
    }
}
