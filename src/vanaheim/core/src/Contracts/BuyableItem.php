<?php declare(strict_types = 1);

namespace Vanaheim\Core\Contracts;

use Money\Money;

interface BuyableItem
{
    public function getType(): string;
    public function getIdentifier();
    public function getDescription(): string;
    public function getUnitPrice(): Money;
    public function getFullPath(): string;
    public function getVatRate(): float;
    public function getRequiresShipping(): bool;
}
