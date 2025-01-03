<?php

namespace App\Features\Automation\Pipes;

use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;

class ResolvedPayment
{
    public function __construct(protected Order $order) {}

    public function __invoke(Invoice $invoice, $next): mixed
    {

        $payments = $this->order->payments()->unresolved()->get();

        foreach ($payments as $payment) {

            $balancePayment = $payment->unresolved_amount - $invoice->unresolved_amount;

            if ($balancePayment > 0) {

                $this->updatePayment($payment, [
                    'unresolved_amount' => $balancePayment,
                ]);

                $this->attachToInvoice($invoice, $payment, $invoice->unresolved_amount);

                $this->updateInvoice($invoice, [
                    'over_paid' => $invoice->unresolved_amount,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => InvoiceStatus::PAID,
                ]);

                break;
            }

            $amountCharged = $payment->unresolved_amount;

            $this->updatePayment($payment, [
                'unresolved' => false,
                'unresolved_amount' => 0,
            ]);

            if ($balancePayment === 0) {

                $this->attachToInvoice($invoice, $payment, $amountCharged);

                $this->updateInvoice($invoice, [
                    'over_paid' => $invoice->amountCharged,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => InvoiceStatus::PAID,
                ]);

                break;
            }

            $this->updateInvoice($invoice, [
                'over_paid' => $invoice->unresolved_amount - abs($balancePayment),
                'unresolved_amount' => abs($balancePayment),
                'status' => InvoiceStatus::PARTIAL_PAID,
            ]);

            $this->attachToInvoice($invoice, $payment, $amountCharged);

            $invoice = $invoice->fresh();
        }

        return $next($invoice->fresh());
    }

    /**
     * Summary of updateInvoice
     */
    private function updateInvoice(Invoice $invoice, array $data): void
    {
        $invoice->update($data);
    }

    /**
     * Summary of updatePayment
     */
    private function updatePayment(Payment $payment, array $data): void
    {
        $payment->update($data);
    }

    /**
     * Summary of attachToInvoice
     */
    private function attachToInvoice(Invoice $invoice, Payment $payment, int $amount): void
    {
        $invoice->payments()->attach($payment, ['amount' => $amount, 'created_at' => now(), 'updated_at' => now()]);
    }
}
