<?php

namespace App\Features\Automation\Validators;

use App\Models\Order;
use Carbon\Carbon;

class CreateLateChargeValidator
{
    public function handle(Carbon $issueAt, Order $order): bool
    {
        if ($order->completed_at !== null) {
            return false;
        }

        $unresolvedAmount = $order->invoices()->unresolved()->sum('unresolved_amount');

        if ($unresolvedAmount === 0) {
            return false;
        }

        if ($unresolvedAmount <= $order->subscription_amount) {

            $lastUnresolvedInvoice = $order->invoices()->unresolved()->latest()->first();

            if ($lastUnresolvedInvoice === null) {
                return false;
            }

            $latestInvoiceDate = Carbon::parse($lastUnresolvedInvoice->issue_at);

            if ($latestInvoiceDate->gt($issueAt)) {
                return false;
            }

            if ($latestInvoiceDate->diffInDays($issueAt) <= 8) {
                return false;
            }
        }

        return true;
    }
}
