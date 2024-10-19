<?php

use App\Actions\Charges\ResolvedCharge;
use App\Actions\Credits\ResolvedCredit;
use App\Actions\Invoices\CreateInvoice;
use App\Actions\Jobs\ProcessInvoice;
use App\Actions\Jobs\ProcessLateCharge;
use App\Actions\Jobs\ProcessPayment;
use App\Actions\Jobs\UpdateInvoiceStatus;
use App\Actions\Payments\ResolvedPayment;
use App\Models\Customer;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('simulate:payment-create', function () {
    $today = Carbon::parse('2021-04-13');

    $customer = Customer::first();
    $amount = mt_rand(1000, $customer->subscription_fee);
    $payment = $customer->payments()
        ->create([
            'reference_no' => Helper::referenceNoConvention('PAY', $customer->id, $today),
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

    (new ProcessPayment)->handle($today->format('Y-m-d'));

    $this->comment('OK');
});

Artisan::command('simulate:invoice-create', function () {
    $runningDate = Carbon::parse('2021-05-23');

    $today = Carbon::parse('2024-05-01');
    while ($runningDate->lte($today)) {

        (new ProcessInvoice(
            new CreateInvoice,
            new ResolvedCharge,
            new ResolvedCredit,
            new ResolvedPayment,
        ))->handle($runningDate->format('Y-m-d'));

        (new ProcessLateCharge)->handle($runningDate->format('Y-m-d'));

        // (new ProcessPayment)->handle($runningDate->format('Y-m-d'));
        $runningDate->addDay();
    }
});

Artisan::command('ar-job:update-invoice-status', function () {
    (new UpdateInvoiceStatus)->handle();
    $this->comment('OK');
});
