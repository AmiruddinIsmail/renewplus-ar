<?php

namespace App\Features\Payments\Services;

use App\Models\Transaction;

class UpdateTransactionStatus
{
    public function handle(array $ids, array $payload): void
    {
        Transaction::query()
            ->where('transactionable_type', 'App\Models\Invoice')
            ->whereIn('transactionable_id', $ids)
            ->update([
                'gateway_status' => $payload['gateway_status'],
                'gateway_id' => $payload['gateway_id'],
            ]);
    }
}
