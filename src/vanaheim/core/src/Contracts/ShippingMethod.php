<?php declare(strict_types = 1);

namespace Vanaheim\Core\Contracts;

interface ShippingMethod
{
    public function getType(): string;
    public function getId();
    public function getPriceExcludingVat();
    public function getPriceIncludingVat();
    public function getTitle(): string;
}
