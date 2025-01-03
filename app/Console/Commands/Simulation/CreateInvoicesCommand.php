<?php

namespace App\Console\Commands\Simulation;

use App\Features\Automation\Facades\InvoiceProcessor;
use App\Features\Automation\Facades\LateChargeProcessor;
use App\Features\Automation\Facades\PaymentProcessor;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulation:create-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('migrate:fresh --seed');

        Order::factory()->count(1)->create([
            'contract_at' => '2023-01-01',
            'subscription_amount' => 10000,
        ]);

        $firstOrder = Order::query()
            ->orderBy('contract_at', 'ASC')
            ->first();

        $runningAt = Carbon::parse($firstOrder->contract_at);

        $paymentAt = [
            ['date' => '2023-01-01', 'amount' => 10000],
            ['date' => '2023-02-03', 'amount' => 10000],
            ['date' => '2023-03-05', 'amount' => 12000],
        ];

        while ($runningAt->lte(today())) {

            InvoiceProcessor::process($runningAt);

            foreach ($paymentAt as $payment) {
                if ($runningAt->format('Y-m-d') === $payment['date']) {
                    PaymentProcessor::process($firstOrder->payments()->create([
                        'customer_id' => $firstOrder->customer_id,
                        'reference_no' => mt_rand(10000000, 99999999),
                        'paid_at' => $payment['date'],
                        'amount' => $payment['amount'],
                        'unresolved' => true,
                        'unresolved_amount' => $payment['amount'],
                    ]));
                }

            }

            LateChargeProcessor::process($runningAt);

            $runningAt->addDay();
        }
    }
}
