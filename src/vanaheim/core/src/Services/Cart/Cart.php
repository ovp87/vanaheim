<?php declare(strict_types = 1);

namespace Vanaheim\Core\Services\Cart;

use ArithmeticError;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Money\{Currencies\ISOCurrencies, Currency, Formatter\DecimalMoneyFormatter, Formatter\IntlMoneyFormatter, Money};
use NumberFormatter;
use Vanaheim\Core\Contracts\BuyableItem;
use Vanaheim\Core\Exceptions\UnsupportedCurrency;

class Cart implements Arrayable {

    protected SessionManager $sessionManager;
    protected Collection $items;
    protected Currency $currency;
    protected Money $total;
    protected Money $vat;
    protected string $localizedTotal;
    protected string $localizedVat;

    protected CartPersonalia $personalia;

    protected bool $requiresShipping = false;

    public function __construct()
    {
        $this->sessionManager = app(SessionManager::class);
        $this->resolveSession();
    }

    public function toArray(): array
    {
        return [
            'personalia' => $this->personalia->toArray(),
            'items' => $this->items,
            'currency' => $this->currency,
            'requiresShipping' => $this->requiresShipping,
            'total' => $this->total->getAmount(),
            'vat' => $this->vat->getAmount(),
            'localizedTotal' => $this->getLocalizedTotal(),
            'localizedVat' => $this->getLocalizedVat(),
        ];
    }

    public function update(CartUpdate $update)
    {
        $updateItems = $update->toArray()['items'] ?? [];

        if (!empty($updateItems)) {
            $this->items = collect();
        }

        foreach ($updateItems as $updateItem) {
            $this->insertNewRow($updateItem['buyableItem'], $updateItem['quantity']);
        }

        $this->personalia = $update->getPersonalia();

        $this->bindSession();
    }

    public function addItem(BuyableItem $item, int $quantity = 1)
    {
        $existingCartItem = $this->getCartItemFor($item);

        if ($existingCartItem) {
            $quantity = $existingCartItem->getQuantity() + $quantity;
            $this->updateQuantityByRowId($existingCartItem->getRowId(), $quantity);
        } else {
            $this->insertNewRow($item, $quantity);
        }

        $this->bindSession();
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getLocalizedVat(): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        $this->localizedVat = $moneyFormatter->format($this->vat);

        return $this->localizedVat;
    }

    public function getLocalizedTotal(): string
    {
        $currencies = new ISOCurrencies();

        $numberFormatter = new NumberFormatter('no', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        $this->localizedTotal = $moneyFormatter->format($this->total);

        return $this->localizedTotal;
    }

    protected function getCartItemFor(BuyableItem $item)
    {
        return $this->items->filter(function(CartItem $cartItem) use ($item) {
            return $cartItem->getIdentifier() === $item->getIdentifier()
                && $cartItem->getType() === $item->getType();
        })->first();
    }

    protected function insertNewRow(BuyableItem $item, int $quantity)
    {
        if ($quantity <= 0) {
            throw new ArithmeticError("Invalid quantity provided, unable to add to cart.");
        }

        $this->items->push(new CartItem($item, $quantity));
    }

    protected function updateQuantityByRowId(string $rowId, int $quantity)
    {

        $item = $this->items->filter(function(CartItem $cartItem) use ($rowId, $quantity) {
            return $cartItem->getRowId() === $rowId;
        })->first();

        if ($item) {
            $quantity <= 0
                ? $this->remove($rowId)
                : $item->setQuantity($quantity);
        }
    }

    protected function aggregate()
    {
        $this->requiresShipping = false;
        try {
            $this->total = new Money(0, $this->currency);
            $this->vat = new Money(0, $this->currency);

            $this->items->each(function(CartItem $item) {

                if ($item->getBuyable()->getRequiresShipping()) {
                    $this->requiresShipping = true;
                }

                $this->total = $this->total->add($item->getSubTotal($this->currency));
                $this->vat = $this->vat->add($item->getVat($this->currency));

            });

        } catch (UnsupportedCurrency $exception) {
            report($exception);
            $this->createSession();
        }

    }

    protected function remove(string $rowId)
    {
        $this->items = $this->items->filter(function(CartItem $item) use ($rowId) {
            return $item->getRowId() !== $rowId;
        });
    }

    protected function resolveSession()
    {
        if ($this->hasSession()) {
            try {

                $this->items = unserialize(
                    $this->sessionManager->get('cart.items')
                );

                $this->currency = new Currency(
                    unserialize($this->sessionManager->get('cart.currency'))
                );

                $this->personalia = $this->sessionManager->has('cart.personalia')
                    ? unserialize($this->sessionManager->get('cart.personalia'))
                    : new CartPersonalia([]);

            } catch (\Exception $e) {
                report($e);
                $this->createSession();
            }
        } else {
            $this->createSession();
        }
    }

    protected function createSession()
    {
        $this->items = new Collection;
        $this->currency = (new Currency(
            config('currency.default_currency_iso4217')
        ));
        $this->bindSession();
    }

    protected function bindSession()
    {
        $this->aggregate();
        $this->sessionManager->put('cart.personalia', serialize($this->personalia));
        $this->sessionManager->put('cart.items', serialize($this->items));
        $this->sessionManager->put('cart.currency', serialize($this->currency->getCode()));
    }

    protected function hasSession(): bool
    {
        return $this->sessionManager->has('cart.items')
            && $this->sessionManager->has('cart.currency');
    }
}
