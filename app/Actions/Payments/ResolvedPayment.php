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

                $this->attachToInvoice($invoice, $payment, $invoice->unresolved_amount);

                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                break;
            }

            $amountCharged = $payment->unresolved_amount;

            $payment->unresolved = false;
            $payment->unresolved_amount = 0;
            $payment->save();

            if ($balancePayment === 0) {
                $this->attachToInvoice($invoice, $payment, $amountCharged);

                $invoice->unresolved = false;
                $invoice->unresolved_amount = 0;
                $invoice->status = Invoice::STATUS_PAID;
                $invoice->save();
                break;
            }

            $this->attachToInvoice($invoice, $payment, $amountCharged);
            $invoice->unresolved_amount = abs($balancePayment);
            $invoice->save();
        }
    }

    private function attachToInvoice(Invoice $invoice, Payment $payment, int $amount): void
    {
        $invoice->payments()->attach($payment, ['amount' => $amount, 'created_at' => now(), 'updated_at' => now()]);
    }
}
