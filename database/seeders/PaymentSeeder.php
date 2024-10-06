<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        foreach($customers as $customer){
            $total = mt_rand(2,$customer->tenure - 1);
            $count = 0;
            while($count < $total){
                $contractDate = $customer->contract_at;
                $contractDate->addDays(mt_rand(0, 30 * $customer->tenure));

                $amount = mt_rand(1000,10000);
                $payment = $customer->payments()->create([
                    'reference_no' => 'PAY-'. $contractDate->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT),
                    'paid_at' => $contractDate,
                    'amount' => $amount,
                    'unresolved_amount' => $amount,
                ]);

                $payment->transactions()->create([
                    'customer_id' => $payment->customer_id,
                    'transaction_at' => $payment->paid_at,
                    'debit' => false,
                    'amount' => $payment->amount,
                ]);

                $count++;
            }
        }
    }
}
