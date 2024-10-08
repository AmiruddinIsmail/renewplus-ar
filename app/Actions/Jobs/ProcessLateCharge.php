<?php

namespace App\Actions\Jobs;

use App\Models\Charge;
use App\Models\Customer;
use App\Utils\Helper;
use Carbon\Carbon;

class ProcessLateCharge
{
    public function handle(?string $date = null): void
    {
        /*
        4. create transactions record
        */

        // 1. execute
        $today = today();
        if ($date) {
            $today = Carbon::parse($date);
        }

        $allowedDay = [7, 14, 21, 28];
        $todayDay = $today->format('d');

        if (! in_array($todayDay, $allowedDay)) {
            return;
        }

        // check for existing record
        if (Charge::where('charged_at', $today)->exists()) {
            return;
        }

        // 2. get customers
        $customers = Customer::query()
            ->withCount([
                'invoices' => function ($builder) {
                    $builder->unresolved();
                },
            ])
            ->withSum([
                'invoices' => function ($builder) {
                    $builder->unresolved();
                },
            ], 'unresolved_amount')
            ->whereHas('invoices', function ($builder) {
                $builder->unresolved();
            })
            ->whereNull('completed_at')
            ->get();

        $runningNo = 1;
        foreach ($customers as $customer) {
            if (
                Charge::isLateChargeable(
                    unresolvedInvoiceAmount: $customer->invoices_sum_unresolved_amount ?? 0,
                    invoiceDate: Carbon::parse($customer->invoices()->latest()->first()->issue_at),
                    lateChargeDate: $today,
                    unresolvedInvoiceCount: $customer->invoices_count,
                )
            ) {
                Charge::create([
                    'customer_id' => $customer->id,
                    'reference_no' => Helper::referenceNoConvention(prefix: 'LATE', runningNo: $runningNo++, today: $today),
                    'type' => Charge::TYPE_LATE,
                    'amount' => 1000,
                    'charged_at' => $today,
                ]);
            }
        }
    }
}
