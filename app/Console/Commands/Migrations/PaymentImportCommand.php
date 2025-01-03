<?php

namespace App\Console\Commands\Migrations;

use App\Imports\Migrations\PaymentImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class PaymentImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:payment-import';

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
        Excel::import(new PaymentImport, storage_path() . '/imports/payments.xlsx');

        return 0;
    }
}
