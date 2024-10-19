<?php

namespace App\Actions\Jobs;

use App\Models\Invoice;

class UpdateInvoiceStatus
{
    public function handle(string $status = Invoice::STATUS_OVERDUE): void
    {
        Invoice::where('status', Invoice::STATUS_PENDING)
            ->where('due_at', '<=', now())
            ->update([
                'status' => $status,
            ]);
    }
}
