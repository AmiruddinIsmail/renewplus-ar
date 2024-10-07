<?php

namespace App\Actions\Jobs;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessPaymentTagging
{
    public function handle(?string $date = null): void
    {
        $today = today();
        if ($date) {
            $today = Carbon::parse($date);
        }

        DB::transaction(function () use ($today): void {
            // list of unresolved payment
            $payments = Payment::query()
                ->withSum('invoices', 'invoice_payment.amount')
                ->unresolved()
                ->where('paid_at', '<=', $today)
                ->get();

            foreach ($payments as $index => $payment) {

                // balance = payment - tagged payments
                $balancePaymentAmount = $payment->amount - ($payment->invoices_sum_invoice_paymentamount + 0);
                if ($balancePaymentAmount <= 0) {
                    $payment->unresolved = false;
                    $payment->save();

                    continue;
                }

                // list of upaid invoices by customer
                $invoices = Invoice::query()
                    ->withSum('payments', 'invoice_payment.amount')
                    ->where('customer_id', $payment->customer_id)
                    ->unresolved()
                    ->get();

                foreach ($invoices as $invoice) {
                    // set invoice paid if already tag with old payments
                    $balanceInvoiceAmount = ($invoice->subscription_fee + $invoice->charge_fee) - ($invoice->payments_sum_invoice_paymentamount + 0);
                    if ($balanceInvoiceAmount <= 0) {
                        $invoice->unresolved = false;
                        $invoice->unresolved_amount = 0;
                        $invoice->status = Invoice::STATUS_PAID;
                        $invoice->save();

                        continue;
                    }

                    // no payment balance break the loop
                    if ($balancePaymentAmount - $balanceInvoiceAmount <= 0) {
                        if ($balancePaymentAmount == $balanceInvoiceAmount) {
                            $invoice->unresolved = false;
                            $invoice->unresolved_amount = 0;
                            $invoice->status = Invoice::STATUS_PAID;
                        } else {
                            $invoice->unresolved_amount = $balanceInvoiceAmount - $balancePaymentAmount;
                            $invoice->status = Invoice::STATUS_PARTIAL_PAID;
                        }
                        $invoice->save();
                        $invoice->payments()->attach($payment, ['amount' => $balancePaymentAmount, 'created_at' => now(), 'updated_at' => now()]);

                        $payment->unresolved = false;
                        $payment->unresolved_amount = 0;
                        $payment->save();
                        break;
                    }

                    // got balance
                    $invoice->unresolved = false;
                    $invoice->unresolved_amount = 0;
                    $invoice->status = Invoice::STATUS_PAID;
                    $invoice->save();
                    $invoice->payments()->attach($payment, ['amount' => $balanceInvoiceAmount, 'created_at' => now(), 'updated_at' => now()]);
                    $balancePaymentAmount = $balancePaymentAmount - $balanceInvoiceAmount;
                    $payment->unresolved_amount = $balancePaymentAmount;
                    $payment->save();
                }
            }
        }, 1);
    }
}
