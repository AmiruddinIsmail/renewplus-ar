<?php

namespace App\Features\Payments\Services;

use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;

class UpdateInvoiceStatus
{
    public function handle(array $ids, ?string $status = null): void
    {
        if ($status === null) {
            $status = InvoiceStatus::PROCESSING;
        }

        Invoice::query()
            ->whereIn('id', $ids)
            ->unresolved()
            ->update(['status' => $status]);

    }
}
