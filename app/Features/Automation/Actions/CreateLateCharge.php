<?php

namespace App\Features\Automation\Actions;

use App\Features\Automation\Validators\CreateLateChargeValidator;
use App\Models\Charge;
use App\Models\Order;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateLateCharge
{
    public $allowedAt = [7, 14, 21, 28];

    private $amountPerCharge = 1000;

    private bool $shouldValidate = false;

    public function validate(): static
    {
        $this->shouldValidate = true;

        return $this;
    }

    public function handle(Carbon $issueAt, Order $order): void
    {
        if ($this->shouldValidate) {
            if (! (new CreateLateChargeValidator)->handle($issueAt, $order)) {
                return;
            }
        }

        DB::transaction(function () use ($issueAt, $order): void {

            $charge = Charge::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'reference_no' => time(),
                'type' => Charge::TYPE_LATE,
                'amount' => $this->amountPerCharge,
                'charged_at' => $issueAt,
            ]);

            $charge->update([
                'reference_no' => Helper::referenceNoConvention(Charge::PREFIX, $charge->id, $issueAt),
            ]);

        });
    }
}
