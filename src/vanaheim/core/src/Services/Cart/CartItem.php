<?php declare(strict_types = 1);

namespace Vanaheim\Core\Services\Cart;

use Money\Currency;
use Money\Money;
use Vanaheim\Core\Contracts\BuyableItem;
use Vanaheim\Core\Exceptions\UnsupportedCurrency;
use Vanaheim\Core\Services\Currency\CurrencyExchange;
use Illuminate\Contracts\Support\Arrayable;

class CartItem implements Arrayable
{
    private string $rowId;
    private mixed $id;
    private int $quantity;
    private BuyableItem $buyable;

    /**
     * CartItem constructor.
     * @param BuyableItem $buyable
     * @param int $quantity
     */
    public function __construct(BuyableItem $buyable, int $quantity = 1)
    {
        $this->buyable = $buyable;
        $this->quantity = $quantity;
        $this->id = $this->buyable->getIdentifier();
        $this->rowId = md5($this->buyable->getType() . $this->buyable->getIdentifier());
    }

    public function toArray(): array
    {
        return [
            'rowId' => $this->rowId,
            'id' => $this->id,
            'quantity' => $this->quantity,
            'buyable' => $this->buyable
        ];
    }

    /**
     * @param Currency $currency
     * @return Money
     * @throws UnsupportedCurrency
     */
    public function getVat(Currency $currency): Money
    {
        $vat = $this->buyable
            ->getUnitPrice()
            ->multiply($this->getQuantity())
            ->multiply($this->buyable->getVatRate());

        if (!$vat->getCurrency()->equals($currency)) {
            $exchange = CurrencyExchange::create($currency->getCode());
            return $exchange->convert($vat, $currency);
        }

        return $vat;
    }

    /**
     * @param Currency $currency
     * @return Money
     * @throws UnsupportedCurrency
     */
    public function getSubTotal(Currency $currency): Money
    {
        $subTotal = $this->buyable
            ->getUnitPrice()
            ->multiply($this->getQuantity());

        if (!$subTotal->getCurrency()->equals($currency)) {
            $exchange = CurrencyExchange::create($currency->getCode());
            return $exchange->convert($subTotal, $currency);
        }

        return $subTotal;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function getIdentifier()
    {
        return $this->getBuyable()->getIdentifier();
    }

    public function getRowId(): string
    {
        return $this->rowId;
    }

    public function getType(): string
    {
        return $this->getBuyable()->getType();
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getBuyable(): BuyableItem
    {
        return $this->buyable;
    }
}
