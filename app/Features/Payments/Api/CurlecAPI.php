<?php

namespace App\Features\Payments\Api;

use App\Features\Payments\Api\Traits\WithCurlecChecksum;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CurlecAPI
{
    use WithCurlecChecksum;

    private string $merchantId;

    private string $merchantKey;

    private string $employeeId;

    private string $domain;

    public function __construct()
    {
        $this->merchantId = config('services.payments.curlec.merchantId');
        $this->merchantKey = config('services.payments.curlec.merchantKey');
        $this->employeeId = config('services.payments.curlec.employeeId');
        $this->domain = config('services.payments.curlec.domain');

    }

    /**
     * New mandate creation link
     */
    public function createMandate(array $data): string
    {
        $url = '/new-mandate';

        $payload = [
            'amount' => $data['amount'],
            'frequency' => 'WEEKLY',
            'maximumFrequency' => '4',
            'purposeOfPayment' => urlencode('Loans'),
            'businessModel' => 'B2C',
            'name' => $data['name'],
            'emailAddress' => $data['email'],
            'idType' => 'NRIC',
            'idValue' => $data['nric'],
            'bankId' => $data['bankId'],
            'merchantId' => $this->merchantId,
            'employeeId' => $this->employeeId,
            'method' => '03',
            'referenceNumber' => $data['reference_number'],
            'effectiveDate' => today()->format('Y-m-d'),
            'merchantUrl' => config('app.url') . '/mandate-success',
        ];

        $payload['checksum'] = $this->generateChecksum($url, $payload);

        return "{$this->domain}{$url}?" . http_build_query($payload);

    }

    /**
     * Summary of createInstantPayment
     */
    public function createInstantPayment(array $payload): string
    {
        $url = '/new-instant-pay';

        $payload = array_merge([
            'method' => '03',
            'merchantId' => $this->merchantId,
            'employeeId' => $this->employeeId,
            'merchantCallbackUrl' => config('app.url') . '/api/webhooks/curlec',
            'merchantUrl' => config('app.url') . '/curlec-instant-pay-response',
        ], $payload);

        $checksum = $this->generateChecksum($url, $payload);

        return $this->domain . $url . '?' . http_build_query($payload) . '&checksum=' . $checksum;
    }

    /**
     * Summary of generateCollection
     */
    public function createCollection(string $date, array $list): Response
    {
        $url = '/curlec-services';

        $payload = [
            'method' => '04',
            'merchantId' => $this->merchantId,
            'data' => json_encode([
                'date' => $date,
                'reminder' => 'false',
                'upload' => 'true',
                'list' => $list,
            ], JSON_UNESCAPED_SLASHES),
        ];

        $checksum = $this->generateChecksum('', $payload);

        $payload['checksum'] = $checksum;

        $response = Http::timeout(60)
            ->withQueryParameters($payload)
            ->post("{$this->domain}{$url}");

        return $response;

    }

    /**
     * Summary of createCollectionJob
     */
    public function createCollectionJob(string $date, array $list): Response
    {
        $url = '/curlec-services/job/collection';

        $payload = [
            'method' => '04',
            'merchantId' => $this->merchantId,
            'date' => $date,
            'reminder' => false,
            'upload' => true,
            'list' => $list,
            // 'callBackUrl' => 'https://car.test/api/webhooks/curlec/collection-job-response',
        ];

        $checksum = $this->generateRecurringJobChecksum($url, $payload);

        $payload['checksum'] = $checksum;

        $response = Http::timeout(60)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post("{$this->domain}{$url}", $payload);

        return $response;
    }

    /**
     * Summary of getCollectionJob
     */
    public function getCollectionJob(string $jobId): Response
    {
        $url = '/curlec-services/job/collection/status';

        $payload = [
            'merchantId' => $this->merchantId,
            'jobId' => $jobId,
        ];

        $payload['checksum'] = $this->generateChecksum($url, $payload);

        $response = Http::timeout(60)
            ->withQueryParameters($payload)
            ->get("{$this->domain}{$url}");

        return $response;

    }
}
