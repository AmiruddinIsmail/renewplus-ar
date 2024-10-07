<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RolePermissionSeeder::class);

        $u = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);

        $u->assignRole('admin');

        Customer::factory()->count(1)->create();

        // set upfront invoice & payment
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $invoice = $customer->invoices()->create([
                'reference_no' => 'INV-'.$customer->contract_at->format('Ymd').'-'.str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                'issue_at' => $customer->contract_at,
                'due_at' => Carbon::parse($customer->contract_at->format('Y-m-d'))->addDay(),
                'subscription_fee' => $customer->subscription_fee,
                'charge_fee' => 0,
                'unresolved' => false,
                'unresolved_amount' => 0,
                'status' => Invoice::STATUS_PAID,
            ]);

            $payment = $customer->payments()->create([
                'reference_no' => 'PAY-'.str_pad($customer->id, 4, '0', STR_PAD_LEFT),
                'paid_at' => $customer->contract_at,
                'amount' => $customer->subscription_fee,
                'unresolved' => false,
                'unresolved_amount' => 0,
            ]);

            $payment->invoices()->attach($invoice, ['amount' => $invoice->subscription_fee, 'created_at' => now(), 'updated_at' => now()]);

            $invoice->transactions()->create([
                'customer_id' => $invoice->customer_id,
                'transaction_at' => $invoice->issue_at,
                'debit' => true,
                'amount' => $invoice->subscription_fee,
            ]);

            $payment->transactions()->create([
                'customer_id' => $payment->customer_id,
                'transaction_at' => $payment->paid_at,
                'debit' => false,
                'amount' => $payment->amount,
            ]);
        }

        // $this->call(PaymentSeeder::class);
        // Invoice::factory()->count(5)->create();
        // Payment::factory()->count(2)->create();

    }
}
