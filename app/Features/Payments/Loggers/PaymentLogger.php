<?php

namespace App\Features\Payments\Loggers;

use Illuminate\Support\Facades\Log;

class PaymentLogger
{
    public const CURLEC_CHANNEL = 'curlec';
    public const PAYMENT_CHANNEL = 'payments';

    public static function info(string $message, array $context = [], string $channel = self::PAYMENT_CHANNEL): void
    {
        Log::channel($channel)->info($message, $context);
    }

    public static function error(string $message, array $context = [], string $channel = self::PAYMENT_CHANNEL): void
    {
        Log::channel($channel)->error($message, $context);
    }
}
