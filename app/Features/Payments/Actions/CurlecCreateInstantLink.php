<?php

namespace App\Features\Payments\Actions;

use App\Features\Payments\Api\CurlecAPI;

class CurlecCreateInstantLink
{
    public function __construct(protected CurlecAPI $api) {}

    public function handle(array $params): string
    {
        return $this->api->createInstantPayment($params);
    }
}
