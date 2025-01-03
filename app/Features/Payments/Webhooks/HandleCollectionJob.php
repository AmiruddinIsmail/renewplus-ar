<?php

namespace App\Features\Payments\Webhooks;

use App\Features\Payments\Loggers\PaymentLogger;
use App\Models\Transaction;

class HandleCollectionJob
{
    /**
     *  array:3 [
        "Status" => array:1 [
            0 => "201"
        ]
        "Response" => array:1 [
            0 => array:11 [
            "jobId" => 185522251
            "dateCreated" => "2024-11-23 17:24:52.022"
            "merchantId" => 7218372
            "dateStarted" => "2024-11-23 17:30:06.779"
            "dateFinished" => "2024-11-23 17:30:08.876"
            "jobResult" => array:3 [
                "Status" => array:1 [
                0 => "201"
                ]
                "Response" => array:1 [
                0 => array:5 [
                    "collection_date" => array:1 [
                    0 => "Mon Nov 25 00:00:00 MYT 2024"
                    ]
                    "collection_status" => array:1 [
                    0 => "WAITING_FOR_BANK_PROCESSING"
                    ]
                    "batch_id" => array:1 [
                    0 => "CFT20241125DD0000018802"
                    ]
                    "fpx_checksum" => array:1 [
                    0 => "7b2c2a3f9b50d50a8dedb9cc54215ed05b293a20029bd9ea6c7aa95b62f2a078"
                    ]
                    "collection_status_code" => array:1 [
                    0 => "-3"
                    ]
                ]
                ]
                "Date" => array:1 [
                0 => "Sat Nov 23 17:30:08 MYT 2024"
                ]
            ]
            "checksum" => array:1 [
                0 => "37e591b43f401d0fb6429e318b3a1b6be8c0dad5ae23b302ad8b277329a84b00"
            ]
            "jobMethod" => "04"
            "jobContent" => array:6 [
                "date" => "23/11/2024 17:24:47"
                "method" => "04"
                "reminder" => false
                "merchantId" => 7218372
                "upload" => true
                "list" => array:1 [
                0 => array:2 [
                    "amount" => "91.83"
                    "refNum" => "Zhq-ccY-gDi"
                ]
                ]
            ]
            "jobType" => "COLLECTION"
            "status" => "FINISHED"
            ]
        ]
        "Date" => array:1 [
            0 => "Sat Nov 23 20:21:36 MYT 2024"
        ]
        ]
     */
    public function handle(array $payload): void
    {
        $response = $payload[0];

        PaymentLogger::info('collection job status webhook: ', $payload);

        $jobId = $response['jobId'];
        if ($response['status'] === 'FINISHED') {
            Transaction::query()
                ->where('gateway_status', 'NEW')
                ->where('gateway_id', $jobId)
                ->update([
                    'gateway_status' => $response['jobResult']['Response'][0]['collection_status'][0],
                    'gateway_id' => $response['jobResult']['Response'][0]['batch_id'][0],
                ]);
        }

    }
}
