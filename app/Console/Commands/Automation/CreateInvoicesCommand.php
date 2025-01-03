<?php

namespace App\Console\Commands\Automation;

use App\Features\Automation\Facades\InvoiceProcessor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-invoices {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create invoice daily based on contract date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = today();

        $dateArg = $this->argument('date');

        if ($dateArg != null) {

            $date = Carbon::parse($dateArg);
        }

        InvoiceProcessor::process(issueAt: $date);
    }
}
