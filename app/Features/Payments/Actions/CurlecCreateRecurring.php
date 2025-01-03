<?php

namespace App\Features\Payments\Actions;

use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Loggers\PaymentLogger;
use App\Features\Payments\Services\UpdateInvoiceStatus;
use App\Features\Payments\Services\UpdateTransactionStatus;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class CurlecCreateRecurring
{
    public function __construct(protected CurlecAPI $api) {}

    public function handle(array $params): void
    {

        $date = $params['date'];
        $invoices = $params['invoices'];

        $payload = $invoices->map(function (Invoice $invoice): array {
            return [
                'refNum' => $invoice->order->payment_reference,
                'amount' => $invoice->convertToDecimal($invoice->unresolved_amount),
            ];
        });

        $response = $this->api->createCollectionJob($date->format('d/m/Y H:i:s'), $payload->toArray());

        if (! $response->ok()) {

            PaymentLogger::error('collection: error => ', ['error' => $response->body()]);

            return;
        }

        PaymentLogger::info('collection: created => ', ['message' => $response->body()]);

        $invoicesIds = $invoices->pluck('id')->toArray();

        $data = $response->collect();

        DB::transaction(function () use ($invoicesIds, $data): void {
            resolve(UpdateInvoiceStatus::class)->handle($invoicesIds);

            resolve(UpdateTransactionStatus::class)->handle($invoicesIds,
                [
                    'gateway_status' => $data['Response'][0]['status'],
                    'gateway_id' => $data['Response'][0]['jobId'],
                ]);
        });

    }
}
