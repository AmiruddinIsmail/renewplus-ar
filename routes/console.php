<?php

use App\Actions\Jobs\ProcessInvoice;
use App\Actions\Jobs\ProcessLateCharge;
use App\Actions\Jobs\ProcessPaymentTagging;
use App\Models\Charge;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('invoice:tagging', function () {
    $today = Carbon::parse('2021-10-30');

    $customer = Customer::first();
    $amount = 10000; //mt_rand(1000, $customer->subscription_fee);
    $payment = $customer->payments()
        ->create([
            'reference_no' => 'PAY-'.$today->format('Ymd').'-'.str_pad($customer->id, 4, '0', STR_PAD_LEFT),
            'paid_at' => $today,
            'amount' => $amount,
            'unresolved_amount' => $amount,
        ]);

    $payment->transactions()
        ->create([
            'customer_id' => $payment->customer_id,
            'transaction_at' => $payment->paid_at,
            'debit' => false,
            'amount' => $payment->amount,
        ]);

    (new ProcessPaymentTagging)->handle($today->format('Y-m-d'));

    $this->comment('OK');
});

Artisan::command('invoice:create', function () {
    // 2021-02-07;
    $runningDate = Carbon::parse('2021-09-29');

    $today = Carbon::parse('2022-07-29');

    while ($runningDate->lte($today)) {
        (new ProcessInvoice)->handle($runningDate->format('Y-m-d'));
        (new ProcessPaymentTagging)->handle($runningDate->format('Y-m-d'));
        (new ProcessLateCharge)->handle($runningDate->format('Y-m-d'));

        $runningDate->addDay();
    }
});

Artisan::command('latecharge:create', function () {
    /*
    4. create transactions record
    */

    // 1. execute
    $today = today();

    $allowedDay = [7, 14, 21, 28];
    $todayDay = $today->format('d');

    if (! in_array($todayDay, $allowedDay)) {
        return;
    }

    // check for existing record
    if (Charge::where('charged_at', $today)->exists()) {
        return $this->comment('Existing record');
    }

    // 2. get customers
    $customers = Customer::query()
        ->withSum(['invoices' => function ($builder) {
            $builder->where('unresolved', true);
        }], 'unresolved_amount')
        ->whereHas('invoices')
        ->whereNull('completed_at')
        ->get();

    $runningNo = 1;
    foreach ($customers as $customer) {
        if (Charge::isLateChargeable(
            unresolvedInvoiceAmount: $customer->invoices_sum_unresolved_amount ?? 0,
            invoiceDate: Carbon::parse($customer->invoices()->latest()->first()->issue_at),
            lateChargeDate: $today,
        )) {
            Charge::create([
                'customer_id' => $customer->id,
                'reference_no' => Charge::referenceNoConvention(runningNo: $runningNo++, today: $today),
                'type' => Charge::TYPE_LATE,
                'amount' => 1000,
                'charged_at' => $today,
            ]);
        }
    }

    $this->comment('OK');
});

Artisan::command('test', function () {
    $c = Customer::query()
        ->withSum(['invoices' => function ($builder) {
            $builder->where('unresolved', true);
        }], 'unresolved_amount')
        ->whereHas('invoices')
        ->whereNull('completed_at')
        ->find(1);
    // dd($c);
    dd(Charge::isLateChargeable($c->invoices_sum_unresolved_amount ?? 0, Carbon::parse('2022-01-14'), Carbon::parse('2022-01-28')));
});
