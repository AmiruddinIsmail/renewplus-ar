<?php

namespace App\Features\Payments\Api\Traits;

use App\Utils\Helper;
use Error;
use Exception;

trait WithCurlecChecksum
{
    /**
     * Summary of generateChecksum
     *
     * @param  string  $params
     */
    public function generateChecksum(string $url, array $payload): string
    {
        if ($url !== '') {
            $url = "{$url}?";
        }

        $encrypted = "{$this->merchantKey}|{$url}" . Helper::convertPayloadToQueryParamsWithoutEncode($payload);

        return hash('sha256', $encrypted);
    }

    public function generateRecurringJobChecksum(string $url, array $payload): string
    {
        $encrypted = "{$this->merchantKey}|{$url}|" . json_encode($payload, JSON_UNESCAPED_SLASHES);

        return hash('sha256', $encrypted);
    }

    public function validateMandateChecksum(array $payload, string $checksum): bool
    {
        try {
            $result = hash('sha256',
                $this->merchantKey . '|' . $payload['fpx_fpxTxnId'] . '|' . $payload['fpx_sellerExOrderNo'] . '|' .
                $payload['fpx_fpxTxnTime'] . '|' . $payload['fpx_sellerOrderNo'] . '|' . $payload['fpx_sellerId'] . '|' .
                $payload['fpx_txnCurrency'] . '|' . $payload['fpx_buyerName'] . '|' . $payload['fpx_buyerBankId'] . '|' .
                $payload['fpx_txnAmount'] . '|' . $payload['fpx_debitAuthCode'] . '|' . $payload['fpx_type']
            );
            info($result . ' | ' . $checksum);

            return $result === $checksum;

        } catch (Exception $e) {
            return false;
        } catch (Error $e) {
            return false;
        }
    }

    public function validateInstantChecksum(array $payload, string $checksum): bool
    {
        try {
            $result = hash('sha256',
                "{$this->merchantKey}|" . $payload['fpx_fpxTxnId'] . '|' . $payload['fpx_sellerExOrderNo'] . '|' .
                $payload['fpx_fpxTxnTime'] . '|' . $payload['fpx_sellerOrderNo'] . '|' . $payload['fpx_sellerId'] . '|' .
                $payload['fpx_txnCurrency'] . '|' . $payload['fpx_txnAmount'] . '|' . $payload['fpx_buyerName'] . '|' .
                $payload['fpx_buyerBankId'] . '|' . $payload['fpx_debitAuthCode'] . '|' . $payload['fpx_type']
            );

            return $result === $checksum;

        } catch (Exception $e) {
            return false;
        } catch (Error $e) {
            return false;
        }
    }
}
