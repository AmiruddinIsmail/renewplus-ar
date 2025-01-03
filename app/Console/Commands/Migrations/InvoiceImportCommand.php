<?php

namespace App\Console\Commands\Migrations;

use App\Imports\Migrations\InvoiceImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:invoice-import';

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
        Excel::import(new InvoiceImport, storage_path() . '/imports/invoices.xlsx');

        return 0;
    }
}
