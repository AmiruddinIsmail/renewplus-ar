<?php

namespace App\Actions\Jobs;

use App\Actions\Charges\ResolvedCharge;
use App\Actions\Credits\ResolvedCredit;
use App\Actions\Invoices\CreateInvoice;
use App\Actions\Payments\ResolvedPayment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProcessInvoice
{
    public function handle(?string $date = null): void
    {
        /*
        TODO
        6. create PDF file
        7. email customer.
        */
        $today = today();

        if ($date) {
            $today = Carbon::parse($date);
        }
        $days = Helper::monthlyAniversaryDays($today);

        // 1. get customers contract
        $customers = Customer::query()
            ->withCount('invoices')
            ->with([
                'charges' => function (Builder $builder) {
                    $builder->where('unresolved', true);
                },
            ])
            ->monthlyAniversary($days)
            ->whereNull('completed_at')
            ->get();

        $runningNo = 1;
        foreach ($customers as $customer) {
            DB::transaction(function () use ($customer, $runningNo, $today): void {

                // 1. create invoice
                $invoice = (new CreateInvoice)->handle(invoiceDate: $today, customer: $customer, data: [
                    'reference_no' => Invoice::referenceNoConvention($runningNo++, $today),
                    'issue_at' => $today,
                    'due_at' => Carbon::parse($today)->addDay(),
                    'subscription_fee' => $customer->subscription_fee,
                    'unresolved_amount' => $customer->subscription_fee,
                ]);

                if ($invoice === null) {
                    return;
                }

                // 2. resolved charges
                (new ResolvedCharge)->handle(customer: $customer, invoice: $invoice);

                // 3. resolved credits
                (new ResolvedCredit)->handle(customer: $customer, invoice: $invoice);

                // 4. resolved payment
                (new ResolvedPayment)->handle(customer: $customer, invoice: $invoice);

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
