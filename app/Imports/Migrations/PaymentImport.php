<?php

namespace App\Imports\Migrations;

use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PaymentImport implements ToCollection, WithChunkReading
{
    /*
    0 => "pay_id"
    1 => "pay_date"
    2 => "pay_method_ref"
    3 => "pay_amt"
    4 => "pay_voided_at"
    5 => "pay_cust_id"
    6 => "reference no"
    */

    public function collection(Collection $collection): void
    {
        $ids = $collection->whereNotNull(6)->pluck(6);

        $orders = Order::query()
            ->whereIn('payment_reference', $ids)
            ->get();

        foreach ($collection->except(0) as $data) {

            $order = $orders->where('payment_reference', $data[6])->first();
            if (! $order) {
                continue;
            }

            $isVoided = $data[4] === 'NULL' ? false : true;

            if ($isVoided) {
                continue;
            }

            $p = $order->payments()
                ->firstOrCreate([
                    'reference_no' => $data[0],
                ], [
                    'customer_id' => $order->id,
                    'paid_at' => Carbon::parse($data[1]),
                    'amount' => (float) $data[3] * 100,
                    'unresolved' => false,
                    'unresolved_amount' => 0,
                ]);

            $order->transactions()
                ->firstOrCreate([
                    'transactionable_id' => $p->id,
                    'transactionable_type' => 'App\Models\Payment',
                ], [
                    'customer_id' => $order->id,
                    'amount' => $p->amount,
                    'debit' => false,
                ]);

            $this->attachToInvoice($p, $order);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function attachToInvoice(Payment $payment, Order $order): void
    {
        $invoice = $order->invoices()
            ->whereDoesntHave('payments')
            // ->whereRaw('(subscription_amount + charge_amount - credit_paid) = ?', [$payment->amount])
            ->where('unresolved', false)
            ->first();

        $payment->invoices()->attach($invoice, ['amount' => ($payment->amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
