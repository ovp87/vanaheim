<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Vanaheim\Core\Contracts\BuyableItem;

class CartUpdate implements Arrayable
{
    protected array $items = [];

    protected CartPersonalia $personalia;

    public function __construct()
    {
        $this->personalia = new CartPersonalia([]);
    }

    public function addBuyable(BuyableItem $buyableItem, int $quantity)
    {
        $this->items[] = [
            'buyableItem' => $buyableItem,
            'quantity' => $quantity
        ];
    }

    public function getPersonalia(): CartPersonalia
    {
        return $this->personalia;
    }

    public function setPersonalia(CartPersonalia $personalia)
    {
        $this->personalia = $personalia;
    }

    public function toArray(): array
    {
        return [
            'items' => $this->items,
            'personalia' => $this->personalia->toArray(),
        ];
    }
}
