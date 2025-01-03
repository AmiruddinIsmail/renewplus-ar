<?php

namespace App\Features\Payments\Actions;

use App\Models\Order;

class CreatePayment
{
    public function handle(Order $order, array $data): void
    {
        $order->payments()->updateOrCreate(
            ['reference_no' => $data['reference_no']],
            [
                'customer_id' => $order->customer_id,
                'paid_at' => $data['paid_at'],
                'amount' => $data['amount'],
                'unresolved' => $data['unresolved'],
                'unresolved_amount' => $data['unresolved_amount'],
            ]
        );

        // call create transaction

    }
}
