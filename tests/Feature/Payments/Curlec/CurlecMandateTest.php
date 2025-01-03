<?php

use App\Features\Payments\Facades\PaymentManager;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('payment-curlec: can generate mandate link', function (): void {
    $order = Order::factory()->create();

    $order->load('customer');

    $data = [
        'amount' => $order->subscription_amount / 100,
        'name' => $order->customer->name,
        'email' => $order->customer->email,
        'nric' => $order->customer->uuid,
        'reference_number' => $order->payment_reference,
        'bankId' => 19,
    ];

    $mandateLink = PaymentManager::driver('curlec')->createMandateLink($data);

    expect($mandateLink)->toBeString();
});

it('payment-curlec: can process mandate response', function (): void {
    Order::factory()->create([
        'subscription_amount' => 64020,
        'payment_reference' => '24387553',
    ]);

    $payload = [
        'curlec_method' => '00',
        'fpx_fpxTxnId' => '2412111735560878',
        'fpx_sellerExOrderNo' => '5Bd-11122024-309',
        'fpx_fpxTxnTime' => 'Wed Dec 11 17:35:56 MYT 2024',
        'fpx_sellerOrderNo' => '24387553',
        'fpx_sellerId' => 'SE00009436',
        'fpx_txnCurrency' => 'MYR',
        'fpx_txnAmount' => '64.02',
        'fpx_buyerName' => "En N@m3 En ()/PyN .-&B''UYER",
        'fpx_buyerBankId' => 'TEST0021',
        'fpx_debitAuthCode' => '00',
        'fpx_type' => 'N',
        'fpx_notes' => null,
        'fpx_checksum' => 'd3ecbdd62022cdf06552da1b0f60a32222f91f2247889b5dc4835fad651b94c2',
    ];

    $response = PaymentManager::driver('curlec')->processMandate($payload);

    expect($response['success'])->toBeTrue();

});

it('payment-curlec: mandate cannot be process (invalid checksum)', function (): void {
    Order::factory()->create([
        'subscription_amount' => 64020,
        'payment_reference' => '24387553',
    ]);

    $payload = [
        'curlec_method' => '00',
        'fpx_fpxTxnId' => '2412111735560878',
        'fpx_sellerExOrderNo' => '5Bd-11122024-309',
        'fpx_fpxTxnTime' => 'Wed Dec 11 17:35:56 MYT 2024',
        'fpx_sellerOrderNo' => '24387553',
        'fpx_sellerId' => 'SE00009436',
        'fpx_txnCurrency' => 'MYR',
        'fpx_txnAmount' => '64.02',
        'fpx_buyerName' => "En N@m3 En ()/PyN .-&B''UYERss",
        'fpx_buyerBankId' => 'TEST0021',
        'fpx_debitAuthCode' => '00',
        'fpx_type' => 'N',
        'fpx_notes' => null,
        'fpx_checksum' => 'd3ecbdd62022cdf06552da1b0f60a32222f91f2247889b5dc4835fad651b94c2',
    ];

    $response = PaymentManager::driver('curlec')->processMandate($payload);

    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Invalid checksum');
});

it('payment-curlec: can process mandate response (wrong debit auth code)', function (): void {
    Order::factory()->create([
        'subscription_amount' => 64020,
        'payment_reference' => '24387553',
    ]);

    $payload = [
        'curlec_method' => '00',
        'fpx_fpxTxnId' => '2412111735560878',
        'fpx_sellerExOrderNo' => '5Bd-11122024-309',
        'fpx_fpxTxnTime' => 'Wed Dec 11 17:35:56 MYT 2024',
        'fpx_sellerOrderNo' => '24387553',
        'fpx_sellerId' => 'SE00009436',
        'fpx_txnCurrency' => 'MYR',
        'fpx_txnAmount' => '64.02',
        'fpx_buyerName' => "En N@m3 En ()/PyN .-&B''UYER",
        'fpx_buyerBankId' => 'TEST0021',
        'fpx_debitAuthCode' => '01',
        'fpx_type' => 'N',
        'fpx_notes' => null,
        'fpx_checksum' => '7ebe0ee31ffdbc70514aadd4a323db60ec961f80721c74fe36207ad82b28efd8',
    ];

    $response = PaymentManager::driver('curlec')->processMandate($payload);

    expect($response['success'])->toBeFalse();
    expect($response['message'])->toBe('Wrong debit auth code');

});
