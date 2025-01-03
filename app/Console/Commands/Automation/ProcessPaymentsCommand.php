<?php

namespace App\Console\Commands\Automation;

use App\Features\Automation\Facades\PaymentProcessor;
use App\Models\Payment;
use Illuminate\Console\Command;

class ProcessPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-payment {paymentId?}';

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
        $paymentId = $this->argument('paymentId') ?? null;

        if ($paymentId === null) {

            PaymentProcessor::process();

            return;
        }

        $payment = Payment::find($paymentId);
        PaymentProcessor::process($payment ?? null);

    }
}
