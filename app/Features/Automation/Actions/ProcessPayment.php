<?php

namespace App\Features\Automation\Actions;

use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class ProcessPayment
{
    public function handle(Payment $payment): void
    {

        $invoices = $payment->order->invoices()->unresolved()->orderBy('id', 'asc')->get();

        $balance = $payment->unresolved_amount;

        DB::transaction(function () use ($invoices, $payment, $balance): void {

            foreach ($invoices as $invoice) {

                $balance = $balance - $invoice->unresolved_amount;

                if ($balance <= 0) {

                    if ($balance === 0) {

                        $this->attachInvoicePayment($invoice, $payment, $invoice->unresolved_amount);

                        $this->updateInvoice($invoice, false, 0);

                    } else {

                        $this->attachInvoicePayment($invoice, $payment, $payment->unresolved_amount);

                        $this->updateInvoice($invoice, true, abs($balance));

                    }

                    $this->updatePayment($payment, false, 0);

                    break;
                }

                $this->attachInvoicePayment($invoice, $payment, $invoice->unresolved_amount);

                $this->updateInvoice($invoice, false, 0);

                $this->updatePayment($payment, true, $balance);

            }
        });
    }

    private function attachInvoicePayment(Invoice $invoice, Payment $payment, float $amount): void
    {
        $invoice->payments()->attach($payment, ['amount' => $amount, 'created_at' => now(), 'updated_at' => now()]);
    }

    private function updateInvoice(Invoice $invoice, bool $unresolved, float $amount): void
    {
        $invoice->update([
            'unresolved' => $unresolved,
            'unresolved_amount' => $amount,
            'status' => $unresolved ? InvoiceStatus::PARTIAL_PAID : InvoiceStatus::PAID,
        ]);
    }

    private function updatePayment(Payment $payment, bool $unresolved, float $amount): void
    {
        $payment->update([
            'unresolved' => $unresolved,
            'unresolved_amount' => $amount,
        ]);
    }
}
