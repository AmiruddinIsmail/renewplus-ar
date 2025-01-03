<?php

namespace App\Console\Commands\Automation;

use App\Features\Automation\Facades\LateChargeProcessor;
use Illuminate\Console\Command;

class ProcessLateChargesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-late-charges {date?}';

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
        $date = $this->argument('date') ?? today();

        LateChargeProcessor::process(issueAt: $date);
    }
}
