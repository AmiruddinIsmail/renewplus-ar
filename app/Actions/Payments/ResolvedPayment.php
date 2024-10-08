<?php

namespace App\Actions\Payments;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;

class ResolvedPayment
{
    public function handle(Customer $customer, Invoice $invoice): void
    {
        $payments = $customer->payments()->unresolved()->get();

        foreach ($payments as $payment) {
            $balancePayment = $payment->unresolved_amount - $invoice->unresolved_amount;

            if ($balancePayment > 0) {

                $payment->unresolved_amount = $balancePayment;
                $payment->save();

                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                $this->attachToInvoice($invoice, $payment);
                break;
            }

            $payment->unresolved = false;
            $payment->unresolved_amount = 0;
            $payment->save();

            if ($balancePayment === 0) {
                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                $this->attachToInvoice($invoice, $payment);
                break;
            }

            $invoice->unresolved_amount = abs($balancePayment);
            $invoice->save();
            $this->attachToInvoice($invoice, $payment);
        }
    }

    private function attachToInvoice(Invoice $invoice, Payment $payment): void
    {
        $invoice->payments()->attach($payment, ['amount' => ($payment->amount - $payment->unresolved_amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
