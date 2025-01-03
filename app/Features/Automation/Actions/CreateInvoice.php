<?php

namespace App\Features\Automation\Actions;

use App\Features\Automation\Pipes\CreateTransaction;
use App\Features\Automation\Pipes\PatchInvoiceReferenceNo;
use App\Features\Automation\Pipes\ResolvedCharge;
use App\Features\Automation\Pipes\ResolvedCredit;
use App\Features\Automation\Pipes\ResolvedPayment;
use App\Features\Automation\Pipes\SendNotification;
use App\Features\Automation\Validators\CreateInvoiceValidator;
use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Pipeline;

class CreateInvoice
{
    private array $pipes = [];
    private bool $shouldValidate = false;

    public function validate(): static
    {
        $this->shouldValidate = true;

        return $this;
    }

    public function handle(Carbon $issueAt, Order $order): void
    {
        if ($this->shouldValidate) {
            if (! (new CreateInvoiceValidator)->handle($issueAt, $order)) {
                return;
            }
        }

        $this->pipes = [
            (new PatchInvoiceReferenceNo),
            (new ResolvedCharge($order)),
            (new ResolvedPayment($order)),
            (new ResolvedCredit($order)),
            (new CreateTransaction($order)),
            (new SendNotification),
        ];

        DB::transaction(function () use ($issueAt, $order): void {

            $invoice = Invoice::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'reference_no' => time() . $order->id,
                'issue_at' => $issueAt->format('Y-m-d'),
                'due_at' => Carbon::parse($issueAt)->addDay()->format('Y-m-d'),
                'subscription_amount' => $order->subscription_amount,
                'status' => InvoiceStatus::PENDING,
                'unresolved_amount' => $order->subscription_amount,
                'amount' => $order->subscription_amount,
            ]);

            Pipeline::send($invoice)
                ->through($this->pipes)
                ->thenReturn();

        });

    }
}
