<?php

namespace App\Actions\Charges;

use App\Models\Customer;
use App\Models\Invoice;

class ResolvedCharge
{
    public function handle(Customer $customer, Invoice $invoice): void
    {
        // unresolved charges
        $charges = $customer->charges()->unresolved()->get();

        if ($charges->sum('amount') == 0) {
            return;
        }

        $invoice->charge_fee = $charges->sum('amount');
        $invoice->unresolved_amount += $charges->sum('amount');
        $invoice->save();

        $customer->charges()
            ->whereIn('id', $charges->pluck('id'))
            ->update([
                'unresolved' => false,
                'invoice_id' => $invoice->id,
            ]);

    }
}
