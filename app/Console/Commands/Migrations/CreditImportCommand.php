<?php

namespace App\Console\Commands\Migrations;

use App\Imports\Migrations\CreditImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class CreditImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:credit-import';

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
        Excel::import(new CreditImport, storage_path() . '/imports/creditnote.xlsx');

        return 0;
    }
}
