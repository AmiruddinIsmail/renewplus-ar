<?php

namespace App\Actions\Jobs;

use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProcessInvoice
{
    public function handle(?string $date = null): void
    {
        /*

            missing on credit logic

        1. get customers based on contract aniversary
        2. get unresolves charges from customer.
        3. create invoices
        4.  run command invoice:tagging for sync with payments
            update charges (unresolved, invoice_id)
        5. create transactions record
        6. create PDF file
        7. email customer.
        */
        $today = today();

        if ($date) {
            $today = Carbon::parse($date);
        }

        $selectedDays = [$today->day];
        if ($today->isEndOfMonth()) {
            if ($today->day == 28) {
                $selectedDays = array_merge($selectedDays, [29, 30, 31]);
            }
            if ($today->day == 29) {
                $selectedDays = array_merge($selectedDays, [30, 31]);
            }
            if ($today->day == 30) {
                $selectedDays = array_merge($selectedDays, [31]);
            }
        }

        // 1. get customers contract
        $customers = Customer::query()
            ->withCount('invoices')
            ->with(['charges' => function (Builder $builder) {
                $builder->where('unresolved', true);
            }])
            ->whereRaw('DAY(contract_at) in (?)', implode(',', $selectedDays))
            ->whereNull('completed_at')
            ->get();

        $runningNo = 1;
        foreach ($customers as $customer) {
            // if customer contract date > today
            if ($customer->contract_at > $today->format('Y-m-d')) {
                continue;
            }

            // invoices completed
            if ($customer->invoices_count >= $customer->tenure) {
                continue;
            }

            // check if duplicated invoice & can eager load
            if ($customer->invoices()->where('issue_at', $today)->exists()) {
                continue;
            }

            DB::transaction(function () use ($customer, $runningNo, $today): void {
                // 2. get unresolved charges
                $sumCharges = $customer->charges->sum('amount') ?? 0;

                // 3.create the invoice
                $invoice = $customer->invoices()
                    ->create([
                        'reference_no' => Invoice::referenceNoConvention($runningNo++, $today),
                        'issue_at' => $today,
                        'due_at' => Carbon::parse($today)->addDay(),
                        'subscription_fee' => $customer->subscription_fee,
                        'charge_fee' => $sumCharges,
                        'unresolved' => true,
                        'unresolved_amount' => ($customer->subscription_fee + $sumCharges),
                    ]);

                // 4.update unresolved charges
                $customer->charges()->where('unresolved', true)->update([
                    'unresolved' => false,
                    'invoice_id' => $invoice->id,
                ]);

                (new ProcessPaymentTagging)->handle($today->format('Y-m-d'));
                $invoice->refresh();

                $invoice->transactions()->create([
                    'customer_id' => $customer->id,
                    'transaction_at' => $today,
                    'debit' => true,
                    'amount' => $invoice->unresolved_amount == 0 ? ($invoice->subscription_fee + $invoice->charge_fee) : $invoice->unresolved_amount,
                ]);

                /*
                5. create transactions record
                6. create PDF file
                7. email customer.
                */

            }, 1);
        }
    }
}
