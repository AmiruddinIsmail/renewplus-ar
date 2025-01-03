<?php

namespace App\Features\Payments\Services;

use App\Features\Payments\Facades\PaymentManager;
use App\Models\Invoice;
use Carbon\Carbon;

class CreateRecurringAutomation
{
    public function handle(?Carbon $runningAt, ?string $driver = null): void
    {
        if ($driver === null) {
            $driver = config('services.payments.default');
        }

        $invoices = Invoice::runnableOn($runningAt, $driver);

        PaymentManager::driver($driver)->createRecurring([
            'date' => $runningAt,
            'invoices' => $invoices,
        ]);
    }
}
