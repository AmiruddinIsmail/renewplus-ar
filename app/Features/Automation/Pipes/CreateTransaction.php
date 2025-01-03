<?php

namespace App\Features\Automation\Pipes;

use App\Models\Invoice;
use App\Models\Order;

class CreateTransaction
{
    public function __construct(protected Order $order) {}

    public function __invoke(Invoice $invoice, $next): mixed
    {
        $invoice->transaction()->updateOrCreate(
            [
                'transactionable_id' => $invoice->id,
                'transactionable_type' => get_class($invoice),
            ],
            [
                'order_id' => $this->order->id,
                'customer_id' => $this->order->customer_id,
                'amount' => $invoice->unresolved_amount,
                'debit' => true,
            ]
        );

        return $next($invoice);
    }
}
