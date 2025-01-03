<?php

namespace App\Features\Automation\Services;

use App\Features\Automation\Actions\ProcessPayment;
use App\Models\Payment;

class PaymentAutomation
{
    public function __construct(protected ProcessPayment $handler) {}

    public function process(?Payment $payment = null): void
    {
        if ($payment !== null) {

            $this->handler->handle($payment);

            return;

        }

        $payments = Payment::query()
            ->with('order')
            ->unresolved()
            ->get();

        foreach ($payments as $payment) {

            $this->handler->handle($payment);

        }
    }
}
