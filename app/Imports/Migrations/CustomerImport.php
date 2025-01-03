<?php

namespace App\Imports\Migrations;

use App\Models\Customer;
use App\Models\Invoice;
use App\Utils\Helper;
use Carbon\Carbon;
use Error;
use Exception;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class CustomerImport implements ToCollection
{
    /*
    0 => "cust_id"
    1 => "cust_fname"
    2 => "cust_lname"
    3 => "cust_email"
    4 => "cust_balance"
    5 => "cust_voided_at"
    6 => "cust_mandate_ref"
    7 => "cust_mandate_status"
    8 => "cust_mandate_max"
    9 => "cust_collection_amt"
    10 => "cust_collection_date"
    11 => "cust_late_charges"
    12 => "cust_late_charges_date"
    13 => "cust_contract_period"
    */
    public function collection(Collection $collection)
    {

        foreach ($collection->except(0)->whereNotIn(6, ['NULL']) as $data) {

            $isVoided = $data[5] === 'NULL' ? false : true;

            if ($isVoided) {
                continue;
            }

            $customer = Customer::firstOrCreate([
                'uuid' => $data[0],
            ], [
                'name' => ucwords(strtolower(trim($data[1]) . ' ' . trim($data[2]))),
                'email' => strtolower(trim($data[3])),
                'status' => 'active',
            ]);

            $customer->orders()->updateOrCreate([
                'payment_reference' => $data[6],
            ], [
                'reference_no' => Helper::referenceNoConvention(Invoice::PREFIX, $customer->id, now()),
                'tenure' => $this->getTenure($data[13]),
                'subscription_amount' => (float) $data[9] * 100,
                'contract_at' => $this->getContractDate($data),
                'payment_gateway' => 'curlec',
                'active' => true,
            ]);
        }
    }

    private function getTenure($tenure): int
    {
        try {
            return $tenure + 1;
        } catch (Exception $e) {
            return 0;
        } catch (Error $e) {
            return 0;
        }
    }

    private function getContractDate($data): Carbon
    {
        try {

            return Carbon::instance(Date::excelToDateTimeObject($data[14]));
        } catch (Exception $e) {
            return Carbon::parse($data[10]);
        } catch (Error $e) {
            return Carbon::parse($data[10]);
        }
    }
}
