<?php declare(strict_types=1);

namespace Vanaheim\Core\Services\Cart;

use Illuminate\Contracts\Support\Arrayable;
use Vanaheim\Core\Contracts\BuyableItem;

class CartUpdate implements Arrayable
{
    protected array $items = [];
    protected array $quantities = [];

    public function add(BuyableItem $buyableItem, int $quantity)
    {
        $this->items[] = $buyableItem;
        $this->quantities[] = $quantity;
    }

    public function toArray(): array
    {
        $arr = [];

        foreach ($this->items as $key => $value) {
            $arr[] = [
                'buyableItem' => $value,
                'quantity' => $this->quantities[$key]
            ];
        }

        return $arr;
    }
}
