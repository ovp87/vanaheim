<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Currency;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Exchange\FixedExchange;
use Vanaheim\Core\Exceptions\UnsupportedCurrencyException;

class CurrencyExchange
{
    public static function create(string $iso4217): Converter
    {
        $currencies = config('currency.supported_currencies');
        $defaultCurrency = config('currency.default_currency_iso4217');

        if (array_key_exists($iso4217, $currencies)) {

            $exchangeRates[$defaultCurrency] = [
                $iso4217 => $currencies[$iso4217]['exchange_rate']
            ];

            return new Converter(
                new ISOCurrencies(),
                new FixedExchange($exchangeRates)
            );
        }

        throw new UnsupportedCurrencyException($iso4217);
    }
}
