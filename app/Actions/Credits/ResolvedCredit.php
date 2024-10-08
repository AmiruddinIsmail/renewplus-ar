<?php

namespace App\Actions\Credits;

use App\Models\Credit;
use App\Models\Customer;
use App\Models\Invoice;

class ResolvedCredit
{
    public function handle(Customer $customer, Invoice $invoice): void
    {
        $credits = $customer->credits()->unresolved()->get();

        foreach ($credits as $credit) {
            $balanceCredit = $credit->unresolved_amount - $invoice->unresolved_amount;

            if ($balanceCredit > 0) {

                $credit->unresolved_amount = $balanceCredit;
                $credit->save();

                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                $this->attachToInvoice($invoice, $credit);
                break;
            }

            $credit->unresolved = false;
            $credit->unresolved_amount = 0;
            $credit->save();

            if ($balanceCredit === 0) {
                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                $this->attachToInvoice($invoice, $credit);
                break;
            }

            $invoice->unresolved_amount = abs($balanceCredit);
            $invoice->save();
            $this->attachToInvoice($invoice, $credit);
        }
    }

    private function attachToInvoice(Invoice $invoice, Credit $credit): void
    {
        $invoice->credits()->attach($credit, ['amount' => ($credit->amount - $credit->unresolved_amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
