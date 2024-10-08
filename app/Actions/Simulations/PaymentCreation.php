<?php

namespace App\Actions\Simulations;

use App\Actions\Jobs\ProcessPayment;
use App\Models\Customer;
use App\Utils\Helper;
use Carbon\Carbon;

class PaymentCreation
{
    public function handle(Carbon $today): void
    {
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
    }
}
