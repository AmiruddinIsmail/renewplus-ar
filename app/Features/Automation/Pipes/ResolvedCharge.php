<?php

namespace App\Features\Automation\Pipes;

use App\Models\Invoice;
use App\Models\Order;

class ResolvedCharge
{
    public function __construct(protected Order $order) {}

    public function __invoke(Invoice $invoice, $next): mixed
    {
        $charges = $this->order->charges()->unresolved()->get();

        if ($charges->sum('amount') === 0) {
            return $next($invoice);
        }

        $invoice->update([
            'charge_amount' => $charges->sum('amount'),
            'unresolved_amount' => $invoice->unresolved_amount + $charges->sum('amount'),
            'amount' => $invoice->unresolved_amount + $charges->sum('amount'),
        ]);

        $this->order->charges()
            ->whereIn('id', $charges->pluck('id'))
            ->update([
                'unresolved' => false,
                'invoice_id' => $invoice->id,
            ]);

        return $next($invoice);
    }
}
