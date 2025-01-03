<?php

namespace App\Console\Commands\Migrations;

use App\Imports\Migrations\CustomerImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class CustomerImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:customer-import';

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
        Excel::import(new CustomerImport, storage_path() . '/imports/customers.xlsx');

        return 0;
    }
}
