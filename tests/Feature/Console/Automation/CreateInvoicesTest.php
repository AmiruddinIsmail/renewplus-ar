<?php

use App\Models\Enums\InvoiceStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

it('command can be executed', function (): void {

    expect(Artisan::call('app:create-invoices'))->toBe(0);
});

it('can create 1 invoices', function (): void {

    $order = Order::factory()->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    expect($order->invoices()->count())->toBe(1);

});

it('can create invoices at end of month (31/01) in month Feb (28/02)', function (): void {

    $order = Order::factory()->create([
        'contract_at' => '2024-10-31',
    ])->fresh();

    Artisan::call('app:create-invoices', [
        'date' => '2025-02-28',
    ]);

    $invoice = $order->invoices()->first();

    expect($invoice->issue_at)->toBe('2025-02-28');

});

it('cannot create invoice if completed is not null', function (): void {

    $order = Order::factory()->create([
        'completed_at' => now(),
    ]);

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    expect($order->invoices()->count())->toBe(0);

});

it('can create N(tenure) numbers of invoices per order', function (): void {

    $order = Order::factory()->create()->fresh();

    $invoiceDate = $order->contract_at;

    $contractEndDate = Carbon::parse($order->contract_at)->addMonths($order->tenure + 2);

    while ($invoiceDate->lte($contractEndDate)) {
        Artisan::call('app:create-invoices', [
            'date' => $invoiceDate,
        ]);

        $invoiceDate->addDay();

    }

    expect($order->invoices()->count())->toBe($order->tenure);

});

it('new invoice must return status pending & amount must same as monthly amount', function (): void {

    $order = Order::factory()->create()->fresh();

    Artisan::call('app:create-invoices', [
        'date' => $order->contract_at,
    ]);

    $invoice = $order->invoices()->first();

    expect($invoice->status)->toBe(InvoiceStatus::PENDING);
    expect($invoice->unresolved_amount)->toBe($order->subscription_amount);

});
