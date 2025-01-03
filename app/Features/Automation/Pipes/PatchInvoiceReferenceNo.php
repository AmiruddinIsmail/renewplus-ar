<?php

namespace App\Features\Automation\Pipes;

use App\Models\Invoice;
use App\Utils\Helper;
use Carbon\Carbon;

class PatchInvoiceReferenceNo
{
    public function __invoke(Invoice $invoice, $next): mixed
    {
        $invoice->update([
            'reference_no' => Helper::referenceNoConvention(Invoice::PREFIX, $invoice->id, Carbon::parse($invoice->issue_at)),
        ]);

        return $next($invoice->fresh());
    }
}
