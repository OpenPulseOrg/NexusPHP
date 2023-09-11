<?php

namespace Nxp\Core\Utils\Conversion;

use Nxp\Core\Config\ConfigurationManager;

class CurrencyConverter
{
    private static $apiEndpoint = 'http://api.exchangeratesapi.io/v1/latest';
    private static $apiKey;

    /**
     * Convert an amount from one currency to another.
     *
     * @param float  $amount      The amount to convert.
     * @param string $fromCurrency The currency to convert from.
     * @param string $toCurrency   The currency to convert to.
     *
     * @return float The converted amount.
     */
    public static function convert($amount, $fromCurrency, $toCurrency)
    {
        self::$apiKey = ConfigurationManager::get('keys', 'EXCHANGE_RATE_API_KEY');

        $exchangeRate = self::fetchExchangeRate($fromCurrency, $toCurrency);
        if ($exchangeRate === null) {
            throw new \InvalidArgumentException("Failed to fetch exchange rate for $fromCurrency to $toCurrency");
        }

        return $amount * $exchangeRate;
    }

    private static function fetchExchangeRate($fromCurrency, $toCurrency)
    {
        $url = self::$apiEndpoint . "?access_key=" . self::$apiKey;

        // Make a request to the exchange rate API and parse the response
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if ($data && isset($data['rates'][$toCurrency])) {
            return $data['rates'][$toCurrency];
        }

        return null;
    }
}
