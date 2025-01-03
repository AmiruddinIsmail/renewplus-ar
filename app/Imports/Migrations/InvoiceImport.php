<?php

namespace App\Imports\Migrations;

use App\Models\Charge;
use App\Models\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class InvoiceImport implements ToCollection, WithChunkReading
{
    /*
    0 => "inv_id"
    1 => "inv_issue_date"
    2 => "inv_due_date"
    3 => "inv_paid_date"
    4 => "inv_total_amt"
    5 => "inv_amt_paid"
    6 => "inv_bal_amt"
    7 => "inv_credit_applied"
    8 => "inv_resolved"
    9 => "inv_voided_at"
    10 => "inv_collection_batch"
    11 => "inv_collection_status"
    12 => "inv_collection_status_desc"
    13 => "inv_description"
    14 => "inv_cust_id"
    15 => "inv_late_charges"
    16 => "Reference No"
    */

    public function collection(Collection $collection): void
    {
        $ids = $collection->except(0)->whereNotNull(16)->pluck(16);

        $orders = Order::query()
            ->whereIn('payment_reference', $ids)
            ->get();

        foreach ($collection->except(0) as $data) {

            $order = $orders->where('payment_reference', $data[16])->first();
            if (! $order) {
                continue;
            }

            $isVoided = $data[9] === 'NULL' ? false : true;

            if ($isVoided) {
                continue;
            }

            // create invoice
            $i = $order->invoices()
                ->firstOrCreate(
                    ['reference_no' => trim($data[0])],
                    [
                        'customer_id' => $order->customer_id,

                        'issue_at' => Carbon::parse(trim($data[1])),
                        'due_at' => Carbon::parse(trim($data[2])),
                        'subscription_amount' => $order->subscription_amount,
                        'charge_amount' => (float) $data[15] * 100,
                        'credit_paid' => (float) $data[7] * 100,
                        'over_paid' => 0,
                        'status' => $data[8] ? InvoiceStatus::PAID : InvoiceStatus::OVERDUE,
                        'unresolved' => ! $data[8],
                        'unresolved_amount' => (float) $data[6] * 100,
                    ]
                );
            if ((float) $data[15] > 0) {
                $this->storeLateCharge((float) $data[15] * 100, $order, $i);

            } else {
                $programFee = $this->storeProgramFee((float) $data[4] * 100, $order, $i);
                if ($programFee > 0) {
                    $i->update(['charge_amount' => $programFee]);
                }

            }

            $order->transactions()
                ->firstOrCreate([
                    'transactionable_id' => $i->id,
                    'transactionable_type' => 'App\Models\Invoice',
                ], [
                    'customer_id' => $order->id,
                    'amount' => ($i->subscription_amount + $i->charge_amount) - ($i->credit_paid + $i->over_paid),
                    'debit' => true,
                ]);

        }

    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function storeProgramFee(int $totalAmount, Order $order, Invoice $invoice): int
    {
        if ($totalAmount <= $order->subscription_amount) {
            return 0;
        }

        $amount = $totalAmount - $order->subscription_amount;

        $order->charges()
            ->firstOrCreate([
                'reference_no' => $invoice->reference_no,
            ], [
                'customer_id' => $order->customer_id,
                'charged_at' => $invoice->issue_at,
                'type' => Charge::TYPE_PROGRAM_FEE,
                'amount' => $amount,
                'unresolved' => false,
                'invoice_id' => $invoice->id,
            ]);

        return $amount;
    }

    private function storeLateCharge(int $chargeFee, Order $order, Invoice $invoice)
    {
        if ($chargeFee <= 0) {
            return;
        }

        $order->charges()
            ->firstOrCreate([
                'reference_no' => $invoice->reference_no,
            ], [
                'customer_id' => $order->customer_id,
                'charged_at' => $invoice->issue_at,
                'type' => Charge::TYPE_LATE,
                'amount' => $chargeFee,
                'unresolved' => false,
                'invoice_id' => $invoice->id,
            ]);
    }
}
