<?php

namespace App\Features\Payments\Drivers;

use App\Features\Payments\Actions\CurlecCreateInstantLink;
use App\Features\Payments\Actions\CurlecCreateMandateLink;
use App\Features\Payments\Actions\CurlecCreateRecurring;
use App\Features\Payments\Actions\CurlecProcessInstant;
use App\Features\Payments\Actions\CurlecProcessMandate;
use App\Features\Payments\Actions\CurlecProcessRecurring;
use App\Features\Payments\Api\CurlecAPI;
use App\Features\Payments\Contracts\PaymentDriver;

class CurlecDriver implements PaymentDriver
{
    public function __construct(protected CurlecAPI $api) {}

    public function createMandateLink(array $params): string
    {
        return (new CurlecCreateMandateLink($this->api))->handle($params);
    }

    public function processMandate(array $params): array
    {
        return (new CurlecProcessMandate($this->api))->handle($params);
    }

    public function createInstantLink(array $params): string
    {
        return (new CurlecCreateInstantLink($this->api))->handle($params);
    }

    public function processInstant(array $params): void
    {
        (new CurlecProcessInstant($this->api))->handle($params);
    }

    public function createRecurring(array $params): void
    {
        (new CurlecCreateRecurring($this->api))->handle($params);
    }

    public function processRecurring(array $params)
    {
        (new CurlecProcessRecurring($this->api))->handle($params);
    }
}
