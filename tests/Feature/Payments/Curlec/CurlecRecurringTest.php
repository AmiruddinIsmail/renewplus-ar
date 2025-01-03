<?php

use App\Features\Automation\Facades\InvoiceProcessor;
use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Facades\PaymentProcessor;
use App\Models\Enums\InvoiceStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// it('curlec-recurring: can create recurring', function (): void {
//     $order = Order::factory()->create([
//         'payment_gateway' => 'curlec',
//         'payment_reference' => 'Zhq-ccY-gDi',
//         'contract_at' => now(),
//     ]);

//     $invoiceDate = Carbon::parse($order->contract_at);

//     InvoiceProcessor::process($invoiceDate, $order);

//     PaymentProcessor::driver('curlec')->createRecurring([
//         'date' => $invoiceDate,
//         'invoices' => $order->invoices()->with('order')->get(),
//     ]);

//     $order->invoices->fresh();

//     expect($order->invoices[0]->status)->toBe(InvoiceStatus::PROCESSING);

// });

it('can check collection status', function (): void {
    $jobId = '185522251';

    $response = resolve(CurlecAPI::class)->getCollectionJob($jobId);
    expect($response->status())->toBe(200);
});
