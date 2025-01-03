<?php

namespace App\Imports\Migrations;

use App\Models\Credit;
use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CreditImport implements ToCollection
{
    /*
    0 => "crd_id"
    1 => "crd_issue_date"
    2 => "crd_total_amt"
    3 => "crd_amt_paid"
    4 => "crd_bal_amt"
    5 => "crd_resolved"
    6 => "crd_voided_at"
    7 => "crd_cust_id"
    8 => "reference_no"
    */

    public function collection(Collection $collection): void
    {

        $ids = $collection->whereNotNull(8)->pluck(8);

        $orders = Order::query()
            ->whereIn('payment_reference', $ids)
            ->get();

        foreach ($collection->except(0) as $data) {

            $order = $orders->where('payment_reference', $data[8])->first();
            if (! $order) {
                continue;
            }

            $isVoided = $data[6] === 'NULL' ? false : true;

            if ($isVoided) {
                continue;
            }

            $c = $order->credits()
                ->firstOrCreate([
                    'reference_no' => $data[0],
                ], [
                    'customer_id' => $order->id,
                    'amount' => (float) $data[2] * 100,
                    'unresolved' => ! $data[5],
                ]);

            $this->attachToInvoice($c, $order);
        }
    }

    private function attachToInvoice(Credit $credit, Order $order): void
    {
        $invoice = $order->invoices()
            ->whereDoesntHave('credits')
            ->where('credit_paid', $credit->amount)
            ->first();

        $credit->invoices()->attach($invoice, ['amount' => ($credit->amount), 'created_at' => now(), 'updated_at' => now()]);
    }
}
