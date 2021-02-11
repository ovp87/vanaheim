<?php declare(strict_types = 1);

namespace Vanaheim\Core\Services\Cart;

use Money\Currency;
use Money\Money;
use Vanaheim\Core\Contracts\ShippingMethod;
use Vanaheim\Core\Services\Currency\CurrencyExchange;

class CartShipping
{
    protected ShippingMethod $shippingMethod;

    public function __construct(ShippingMethod $shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
    }

    public function getType(): string
    {
        return $this->shippingMethod->getType();
    }

    public function getId()
    {
        return $this->shippingMethod->getId();
    }

    public function getPriceExcludingVat(Currency $currency): Money
    {
        $subTotal = $this->shippingMethod->getPriceExcludingVat();

        if (!$subTotal->getCurrency()->equals($currency)) {
            $exchange = CurrencyExchange::create($currency->getCode());
            return $exchange->convert($subTotal, $currency);
        }

        return $subTotal;
    }

    public function getPriceIncludingVat(Currency $currency): Money
    {
        $subTotal = $this->shippingMethod->getPriceIncludingVat();

        if (!$subTotal->getCurrency()->equals($currency)) {
            $exchange = CurrencyExchange::create($currency->getCode());
            return $exchange->convert($subTotal, $currency);
        }

        return $subTotal;
    }

    public function getTitle(): string
    {
        return $this->shippingMethod->getTitle();
    }
}
