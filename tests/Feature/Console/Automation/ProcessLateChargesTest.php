<?php

use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

it('command can be executed', function (): void {

    expect(Artisan::call('app:process-late-charges'))->toBe(0);
});

it('can has grace period', function (): void {

    $order = Order::factory([
        'contract_at' => '2025-01-01',
    ])->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    // call on 07 (grace period)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(6),
    ]);

    expect($order->charges()->count())->toBe(0);

});

it('cannot has grace period(has outstanding amount) (late: 1000)', function (): void {

    $order = Order::factory([
        'contract_at' => '2025-01-01',
    ])->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    $nextInvoiceDate = Carbon::parse($order->contract_at)->addMonth();

    Artisan::call('app:create-invoices', [
        'date' => $nextInvoiceDate,
    ]);

    // call on 07 (charge 1000)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($nextInvoiceDate)->addDays(6),
    ]);

    expect($order->charges()->count())->toBe(1);

});

it('can has grace period(no outstanding amount)', function (): void {

    $order = Order::factory([
        'contract_at' => '2025-01-01',
    ])->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    // simulate payment
    $payment = $order->payments()->create([
        'customer_id' => $order->customer_id,
        'reference_no' => Helper::referenceNoConvention('PAY', mt_rand(1, 9999), today()),
        'amount' => $order->subscription_amount,
        'paid_at' => $order->contract_at,
        'unresolved' => true,
        'unresolved_amount' => $order->subscription_amount,
    ]);

    Artisan::call('app:process-payment', [
        'paymentId' => $payment->id,
    ]);

    $nextInvoiceDate = Carbon::parse($order->contract_at)->addMonth();

    Artisan::call('app:create-invoices', [
        'date' => $nextInvoiceDate,
    ]);

    // call on 07 (charge 1000)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($nextInvoiceDate)->addDays(6),
    ]);

    expect($order->charges()->count())->toBe(0);

});

it('can process late charges (late: 1000)', function (): void {

    $order = Order::factory([
        'contract_at' => '2025-01-01',
    ])->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    // call on 07 (grace period)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(6),
    ]);

    // call on 14
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(13),
    ]);

    expect($order->charges()->count())->toBe(1);

});

it('next invoice generated include late (late: 3000)', function (): void {

    $order = Order::factory([
        'contract_at' => '2025-01-01',
    ])->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    // call on 07 (grace)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(6),
    ]);

    // call on 14 (1000)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(13),
    ]);

    // call on 21 (1000)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(20),
    ]);

    // call on 28 (1000)
    Artisan::call('app:process-late-charges', [
        'date' => Carbon::parse($order->contract_at)->addDays(27),
    ]);

    $nextInvoiceDate = Carbon::parse($order->contract_at)->addMonth();

    Artisan::call('app:create-invoices', [
        'date' => $nextInvoiceDate,
    ]);

    expect($order->charges()->count())->toBe(3);
    expect($order->invoices[1]->charge_amount)->toBe(3000);
    expect($order->invoices[1]->unresolved_amount)->toBe($order->subscription_amount + 3000);

});
