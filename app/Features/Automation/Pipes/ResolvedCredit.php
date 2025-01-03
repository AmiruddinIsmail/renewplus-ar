<?php

namespace App\Features\Automation\Pipes;

use App\Models\Credit;
use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;

class ResolvedCredit
{
    public function __construct(protected Order $order) {}

    public function __invoke(Invoice $invoice, $next): mixed
    {

        $credits = $this->order->credits()->unresolved()->get();

        foreach ($credits as $credit) {

            $unresolvedInvoiceAmount = $invoice->unresolved_amount;
            $unresolvedCreditAmount = $credit->unresolved_amount;

            $balanceCredit = $credit->unresolved_amount - $invoice->unresolved_amount;

            if ($balanceCredit > 0) {

                $credit->unresolved_amount = $balanceCredit;
                $credit->save();

                $this->updateInvoice($invoice, [
                    'credit_paid' => $unresolvedInvoiceAmount,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => InvoiceStatus::PAID,
                ]);
                $this->attachToInvoice($invoice, $credit);

                break;
            }

            $credit->unresolved = false;
            $credit->unresolved_amount = 0;
            $credit->save();

            if ($balanceCredit === 0) {

                $this->updateInvoice($invoice, [
                    'credit_paid' => $unresolvedInvoiceAmount,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                    'status' => InvoiceStatus::PAID,
                ]);

                $this->attachToInvoice($invoice, $credit);

                break;
            }

            $this->updateInvoice($invoice, [
                'credit_paid' => $unresolvedCreditAmount,
                'unresolved_amount' => abs($balanceCredit),
            ]);

            $this->attachToInvoice($invoice, $credit);
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
     * Summary of attachToInvoice
     */
    private function attachToInvoice(Invoice $invoice, Credit $credit): void
    {
        $invoice->credits()->attach($credit, ['amount' => ($credit->amount - $credit->unresolved_amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
