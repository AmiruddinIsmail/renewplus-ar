<?php

namespace App\Features\Payments\Actions;

use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Loggers\PaymentLogger;
use App\Models\Order;

class CurlecProcessMandate
{
    protected $bankNamePercentage = 1;

    protected $mandateAmount = 100;

    public function __construct(protected CurlecAPI $api) {}

    public function handle(array $params): array
    {
        PaymentLogger::info('mandate-response:', $params, PaymentLogger::CURLEC_CHANNEL);

        $order = Order::query()
            ->with('customer')
            ->where('payment_reference', $params['fpx_sellerOrderNo'])
            ->first();

        if ($order === null) {

            PaymentLogger::info('mandate-response: order not found => ' . $params['fpx_sellerOrderNo']);

            return [
                'success' => false,
                'message' => 'Order not found',
            ];
        }

        $validated = $this->validator($order, $params);

        if ($validated['success'] === false) {

            return $validated;
        }

        (new CreatePayment)->handle($order, [
            'reference_no' => $params['fpx_fpxTxnId'],
            'paid_at' => now(),
            'amount' => $this->mandateAmount,
            'unresolved' => false,
            'unresolved_amount' => 0,
        ]);

        return [
            'success' => true,
            'message' => 'OK',
        ];

    }

    private function validator(Order $order, array $params): array
    {
        $result = $this->api->validateMandateChecksum($params, $params['fpx_checksum']);

        if (! $result) {

            PaymentLogger::info('mandate-response: invalid checksum => ' . $params['fpx_checksum']);

            return [
                'success' => false,
                'message' => 'Invalid checksum',
            ];
        }

        if ($params['fpx_debitAuthCode'] !== '00') {

            PaymentLogger::info('mandate-response: Wrong debit auth code => ' . $params['fpx_debitAuthCode']);

            return [
                'success' => false,
                'message' => 'Wrong debit auth code',
            ];
        }

        similar_text($order->customer->name, $params['fpx_buyerName'], $percentage);

        if ($percentage < $this->bankNamePercentage) {

            PaymentLogger::info('mandate-response: Unmatch bank name => ' . $params['fpx_buyerName']);

            return [
                'success' => false,
                'message' => 'Unmatch bank name',
            ];

        }

        return [
            'success' => true,
            'message' => 'OK',
        ];
    }
}
