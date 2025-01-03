<?php

namespace App\Features\Payments\Actions;

use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Loggers\PaymentLogger;
use App\Models\Order;
use Exception;

class CurlecProcessInstant
{
    public function __construct(protected CurlecAPI $api) {}

    public function handle(array $params): void
    {
        PaymentLogger::info('instant-payment:', $params, PaymentLogger::CURLEC_CHANNEL);

        PaymentLogger::info('instant-payment:', $params);

        $result = $this->api->validateInstantChecksum($params, $params['fpx_checksum']);

        if (! $result) {

            PaymentLogger::info('instant-payment: invalid checksum => ' . $params['fpx_checksum']);

            return;
        }

        $order = Order::query()
            ->where('reference_no', $params['fpx_sellerOrderNo'])
            ->first();

        if (! $order) {

            PaymentLogger::info('instant-payment: order not found => ' . $params['fpx_sellerOrderNo']);

            return;
        }

        $payment = $order->payments()
            ->where('reference_no', $params['fpx_fpxTxnId'])
            ->exists();

        if ($payment) {

            PaymentLogger::info('instant-payment: exists => ' . $params['fpx_fpxTxnId']);

            return;
        }

        // $c = Carbon::createFromFormat('D M d H:i:s Y', 'Thu Nov 14 19:48:42 MYR 2024');

        try {

            (new CreatePayment)->handle($order, [
                'reference_no' => $params['fpx_fpxTxnId'],
                'paid_at' => now(),
                'amount' => (float) $params['fpx_txnAmount'] * 100,
                'unresolved' => true,
                'unresolved_amount' => (float) $params['fpx_txnAmount'] * 100,
            ]);

            PaymentLogger::info('instant-payment: created => ' . $params['fpx_fpxTxnId']);

        } catch (Exception $e) {

            PaymentLogger::error('instant-payment: error => ', ['error' => $e->getMessage()]);

        }
    }
}
