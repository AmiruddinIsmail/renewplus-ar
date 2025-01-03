<?php

namespace App\Features\Payments\Actions;

use App\Features\Payments\Api\CurlecAPI;

class CurlecProcessRecurring
{
    public function __construct(protected CurlecAPI $api) {}

    public function handle(array $params): void
    {
        info('processed');
    }
}
