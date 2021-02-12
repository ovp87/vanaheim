<?php declare(strict_types=1);

namespace Vanaheim\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Money\Currency;
use Money\Money;
use Vanaheim\Core\Contracts\BuyableItem;
use Vanaheim\Core\Services\Cart\Cart;

class ProductTest extends Model implements BuyableItem
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    public function buyable(): morphMany
    {
        return $this->morphMany(Buyable::class, 'buyable');
    }

    public function getType(): string
    {
        return static::class;
    }

    public function getUnitPrice(): Money
    {
        $currency = app(Cart::class)->getCurrency();
        return new Money($this->price, $currency);
    }

    public function getRequiresShipping(): bool
    {
        return true;
    }

    public function getIdentifier()
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->title;
    }

    public function getFullPath(): string
    {
        return $this->buyable->url;
    }

    public function getVatRate(): float
    {
        return 0.25;
    }

}
