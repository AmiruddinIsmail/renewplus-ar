<?php

namespace App\Features\Payments\Contracts;

interface PaymentDriver
{
    public function createMandateLink(array $params);

    public function processMandate(array $params);

    public function createInstantLink(array $params);

    public function processInstant(array $params);

    public function createRecurring(array $params);

    public function processRecurring(array $params);
}
