<?php

namespace App\Actions\Jobs;

use App\Actions\Charges\ResolvedCharge;
use App\Actions\Credits\ResolvedCredit;
use App\Actions\Invoices\CreateInvoice;
use App\Actions\Payments\ResolvedPayment;
use App\Models\Customer;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessInvoice
{
    public function __construct(
        private CreateInvoice $createInvoiceAction,
        private ResolvedCharge $resolvedChargeAction,
        private ResolvedCredit $resolvedCreditAction,
        private ResolvedPayment $resolvedPaymentAction
    ) {}

    public function handle(?string $date = null): void
    {
        /*
            create PDF file
            email customer.
        */

        $today = today();
        if ($date) {
            $today = Carbon::parse($date);
        }

        $days = Helper::monthlyAniversaryDays($today);

        $customers = Customer::query()
            ->monthlyAniversary($days)
            ->whereNull('completed_at')
            ->get();

        $runningNo = 1;
        foreach ($customers as $customer) {
            DB::transaction(function () use ($customer, $runningNo, $today): void {

                $invoice = $this->createInvoiceAction->handle(invoiceDate: $today, customer: $customer, data: [
                    'reference_no' => Helper::referenceNoConvention('INV', $runningNo++, $today),
                    'issue_at' => $today,
                    'due_at' => Carbon::parse($today)->addDay(),
                    'subscription_fee' => $customer->subscription_fee,
                    'unresolved_amount' => $customer->subscription_fee,
                ]);

                if ($invoice === null) {
                    return;
                }

                $this->resolvedChargeAction->handle(customer: $customer, invoice: $invoice);

                $this->resolvedCreditAction->handle(customer: $customer, invoice: $invoice);

                $this->resolvedPaymentAction->handle(customer: $customer, invoice: $invoice);

                $invoice->refresh();
                $invoice->transactions()->create([
                    'customer_id' => $customer->id,
                    'transaction_at' => $today,
                    'debit' => true,
                    'amount' => $invoice->unresolved_amount,
                ]);

            }, 1);
        }
    }
}
